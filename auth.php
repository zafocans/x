<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? 1 : 0);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.use_only_cookies', 1);
    ini_set('session.gc_maxlifetime', 3600);
    session_start();
}

define('AUTH_MAX_LOGIN_ATTEMPTS', 5);
define('AUTH_LOCKOUT_TIME', 900);
define('AUTH_SESSION_LIFETIME', 3600);

require_once __DIR__ . '/../../api/config.php';

function getAdminByUsername($username) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, username, password_hash FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function isLoggedIn() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        return false;
    }
    
    if (!isset($_SESSION['admin_login_time'])) {
        return false;
    }
    
    if (time() - $_SESSION['admin_login_time'] > AUTH_SESSION_LIFETIME) {
        logout();
        return false;
    }
    
    if (!isset($_SESSION['admin_ip']) || $_SESSION['admin_ip'] !== getClientIP()) {
        logout();
        return false;
    }
    
    if (!isset($_SESSION['admin_user_agent']) || $_SESSION['admin_user_agent'] !== md5($_SERVER['HTTP_USER_AGENT'] ?? '')) {
        logout();
        return false;
    }
    
    $_SESSION['admin_last_activity'] = time();
    
    return true;
}

function requireAuth() {
    if (!isLoggedIn()) {
        if (isAjaxRequest()) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        header('Location: login.php');
        exit;
    }
    
    regenerateSessionIfNeeded();
}

function login($username, $password) {
    $ip = getClientIP();
    
    if (isLockedOut($ip)) {
        logSecurityEvent('login_blocked', ['username' => $username, 'reason' => 'lockout']);
        return ['success' => false, 'error' => 'Çok fazla başarısız deneme. Lütfen bekleyin.'];
    }
    
    $username = trim($username);
    $password = trim($password);
    
    if (empty($username) || empty($password)) {
        return ['success' => false, 'error' => 'Kullanıcı adı ve şifre gerekli.'];
    }
    
    if (strlen($username) > 50 || strlen($password) > 100) {
        return ['success' => false, 'error' => 'Geçersiz giriş bilgileri.'];
    }
    
    $admin = getAdminByUsername($username);
    
    usleep(random_int(100000, 300000));
    
    if ($admin && password_verify($password, $admin['password_hash'])) {
        clearLoginAttempts($ip);
        
        session_regenerate_id(true);
        
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_login_time'] = time();
        $_SESSION['admin_last_activity'] = time();
        $_SESSION['admin_ip'] = $ip;
        $_SESSION['admin_user_agent'] = md5($_SERVER['HTTP_USER_AGENT'] ?? '');
        $_SESSION['admin_session_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        logSecurityEvent('login_success', ['username' => $username]);
        
        return ['success' => true];
    }
    
    recordLoginAttempt($ip);
    logSecurityEvent('login_failed', ['username' => $username]);
    
    return ['success' => false, 'error' => 'Geçersiz kullanıcı adı veya şifre.'];
}

function logout() {
    $username = $_SESSION['admin_username'] ?? 'unknown';
    
    $_SESSION = [];
    
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    
    session_destroy();
    
    logSecurityEvent('logout', ['username' => $username]);
    
    if (!isAjaxRequest()) {
        header('Location: login.php');
        exit;
    }
}

function isLockedOut($ip) {
    $db = getDB();
    
    $stmt = $db->prepare("SELECT attempts, locked_until FROM login_attempts WHERE ip = ?");
    $stmt->execute([$ip]);
    $record = $stmt->fetch();
    
    if (!$record) {
        return false;
    }
    
    if ($record['locked_until'] && strtotime($record['locked_until']) > time()) {
        return true;
    }
    
    if ($record['attempts'] >= AUTH_MAX_LOGIN_ATTEMPTS && !$record['locked_until']) {
        $stmt = $db->prepare("UPDATE login_attempts SET locked_until = DATE_ADD(NOW(), INTERVAL ? SECOND) WHERE ip = ?");
        $stmt->execute([AUTH_LOCKOUT_TIME, $ip]);
        return true;
    }
    
    return false;
}

function recordLoginAttempt($ip) {
    $db = getDB();
    
    $stmt = $db->prepare("SELECT id, attempts FROM login_attempts WHERE ip = ?");
    $stmt->execute([$ip]);
    $record = $stmt->fetch();
    
    if ($record) {
        $stmt = $db->prepare("UPDATE login_attempts SET attempts = attempts + 1, last_attempt = NOW() WHERE ip = ?");
        $stmt->execute([$ip]);
    } else {
        $stmt = $db->prepare("INSERT INTO login_attempts (ip, attempts, last_attempt) VALUES (?, 1, NOW())");
        $stmt->execute([$ip]);
    }
}

function clearLoginAttempts($ip) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM login_attempts WHERE ip = ?");
    $stmt->execute([$ip]);
}

function regenerateSessionIfNeeded() {
    $regenerateInterval = 300;
    
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
        return;
    }
    
    if (time() - $_SESSION['last_regeneration'] > $regenerateInterval) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}

function getCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function getRemainingLockoutTime($ip) {
    $db = getDB();
    
    $stmt = $db->prepare("SELECT locked_until FROM login_attempts WHERE ip = ?");
    $stmt->execute([$ip]);
    $record = $stmt->fetch();
    
    if (!$record || !$record['locked_until']) {
        return 0;
    }
    
    $lockedUntil = strtotime($record['locked_until']);
    $remaining = $lockedUntil - time();
    
    return max(0, $remaining);
}


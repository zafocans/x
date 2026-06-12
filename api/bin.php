<?php
/**
 * api/bin.php — Bank Identification Number (BIN) lookup
 *
 * Returns card scheme, type, and bank information for a given 6-digit BIN.
 * In production this would query a BIN database or third-party API.
 * This stub returns a generic response so the UI renders without errors.
 *
 * GET ?bin=XXXXXX
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$bin = preg_replace('/\D/', '', $_GET['bin'] ?? '');

if (strlen($bin) < 6) {
    echo json_encode(['success' => false, 'error' => 'Geçersiz BIN']);
    exit;
}

// Determine scheme from first digit
$firstDigit = (int)$bin[0];
$scheme = 'unknown';
if ($firstDigit === 4) {
    $scheme = 'visa';
} elseif ($firstDigit === 5) {
    $scheme = 'mastercard';
} elseif ($firstDigit === 3) {
    $scheme = 'amex';
}

echo json_encode([
    'success' => true,
    'bin'     => $bin,
    'data'    => [
        'bank'       => '',
        'bank_logo'  => null,
        'scheme'     => $scheme,
        'card_type'  => 'credit',
        'sub_type'   => 'personal',
    ],
]);

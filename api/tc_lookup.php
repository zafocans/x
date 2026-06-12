<?php
/**
 * api/tc_lookup.php — TC Kimlik No lookup stub
 *
 * In a real deployment this would call the NVI (Nüfus ve Vatandaşlık İşleri)
 * web service. Here we return a plausible-looking response so the UI works
 * without an external dependency.
 *
 * GET ?tc=XXXXXXXXXXX
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$tc = trim($_GET['tc'] ?? '');

// Basic format check
if (!preg_match('/^\d{11}$/', $tc) || $tc[0] === '0') {
    echo json_encode(['success' => false, 'error' => 'Geçersiz TC Kimlik No']);
    exit;
}

// Luhn-style TC checksum validation
$digits   = array_map('intval', str_split($tc));
$oddSum   = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8];
$evenSum  = $digits[1] + $digits[3] + $digits[5] + $digits[7];
$check10  = ((($oddSum * 7) - $evenSum) % 10 + 10) % 10;
$totalSum = array_sum(array_slice($digits, 0, 10));

if ($check10 !== $digits[9] || ($totalSum % 10) !== $digits[10]) {
    echo json_encode(['success' => false, 'error' => 'Geçersiz TC Kimlik No']);
    exit;
}

// Return a stub name — in production this comes from the NVI service
echo json_encode([
    'success' => true,
    'tc'      => $tc,
    'ad'      => 'AD',
    'soyad'   => 'SOYAD',
]);

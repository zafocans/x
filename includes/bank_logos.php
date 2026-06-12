<?php
/**
 * includes/bank_logos.php
 *
 * Returns a publicly accessible logo URL for a given bank name.
 * Falls back to null when no match is found so callers can render
 * a text fallback instead.
 */

function getBankLogo(?string $bankName): ?string {
    if (!$bankName) {
        return null;
    }

    $name = strtoupper(trim($bankName));

    $logos = [
        'AKBANK'          => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Akbank_logo.svg/320px-Akbank_logo.svg.png',
        'GARANTI'         => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Garanti_BBVA_logo.svg/320px-Garanti_BBVA_logo.svg.png',
        'YAPI KREDI'      => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Yap%C4%B1_Kredi_logo.svg/320px-Yap%C4%B1_Kredi_logo.svg.png',
        'YAPIKREDI'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Yap%C4%B1_Kredi_logo.svg/320px-Yap%C4%B1_Kredi_logo.svg.png',
        'IS BANKASI'      => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/T%C3%BCrkiye_%C4%B0%C5%9F_Bankas%C4%B1_logo.svg/320px-T%C3%BCrkiye_%C4%B0%C5%9F_Bankas%C4%B1_logo.svg.png',
        'ISBANKASI'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/T%C3%BCrkiye_%C4%B0%C5%9F_Bankas%C4%B1_logo.svg/320px-T%C3%BCrkiye_%C4%B0%C5%9F_Bankas%C4%B1_logo.svg.png',
        'ZIRAAT'          => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6e/Ziraat_Bankas%C4%B1_logo.svg/320px-Ziraat_Bankas%C4%B1_logo.svg.png',
        'VAKIFLAR'        => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8e/Vakifbank_logo.svg/320px-Vakifbank_logo.svg.png',
        'VAKIFBANK'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8e/Vakifbank_logo.svg/320px-Vakifbank_logo.svg.png',
        'HALK'            => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Halkbank_logo.svg/320px-Halkbank_logo.svg.png',
        'HALKBANK'        => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Halkbank_logo.svg/320px-Halkbank_logo.svg.png',
        'QNB'             => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e9/QNB_Finansbank_logo.svg/320px-QNB_Finansbank_logo.svg.png',
        'FINANSBANK'      => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e9/QNB_Finansbank_logo.svg/320px-QNB_Finansbank_logo.svg.png',
        'DENIZBANK'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/DenizBank_logo.svg/320px-DenizBank_logo.svg.png',
        'TEB'             => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/T%C3%BCrk_Ekonomi_Bankas%C4%B1_logo.svg/320px-T%C3%BCrk_Ekonomi_Bankas%C4%B1_logo.svg.png',
        'ING'             => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/49/ING_Group_N.V._Logo.svg/320px-ING_Group_N.V._Logo.svg.png',
        'HSBC'            => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/aa/HSBC_logo_%282018%29.svg/320px-HSBC_logo_%282018%29.svg.png',
        'KUVEYT'          => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8e/Kuveyt_T%C3%BCrk_logo.svg/320px-Kuveyt_T%C3%BCrk_logo.svg.png',
        'ALBARAKA'        => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Albaraka_T%C3%BCrk_logo.svg/320px-Albaraka_T%C3%BCrk_logo.svg.png',
        'SEKERBANK'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6e/%C5%9Eekerbank_logo.svg/320px-%C5%9Eekerbank_logo.svg.png',
        'ODEABANK'        => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e9/Odeabank_logo.svg/320px-Odeabank_logo.svg.png',
        'PAPARA'          => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8e/Papara_logo.svg/320px-Papara_logo.svg.png',
        'PTT'             => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/PTT_logo.svg/320px-PTT_logo.svg.png',
        'TURKIYE FINANS'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6e/T%C3%BCrkiye_Finans_logo.svg/320px-T%C3%BCrkiye_Finans_logo.svg.png',
        'VAKIF KATILIM'   => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8e/Vakif_Kat%C4%B1l%C4%B1m_logo.svg/320px-Vakif_Kat%C4%B1l%C4%B1m_logo.svg.png',
        'ZIRAAT KATILIM'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6e/Ziraat_Kat%C4%B1l%C4%B1m_logo.svg/320px-Ziraat_Kat%C4%B1l%C4%B1m_logo.svg.png',
    ];

    foreach ($logos as $key => $url) {
        if (strpos($name, $key) !== false) {
            return $url;
        }
    }

    return null;
}

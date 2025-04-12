<?php

// URL yang ingin diakses
$url = "https://www.idx.co.id/primary/ListedCompany/GetCompanyProfiles?emitenType=s&start=0&length=9999";

// Inisialisasi cURL
$ch = curl_init();

// Mengatur opsi cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Jika ada redirect
curl_setopt($ch, CURLOPT_HEADER, true); // Agar header termasuk dalam respons

// Eksekusi cURL
$response = curl_exec($ch);

// Periksa jika cURL gagal
if($response === false) {
    echo "cURL Error: " . curl_error($ch);
    exit;
}

// Ambil header dari respons untuk memeriksa status dan konten
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);

// Tutup cURL
curl_close($ch);

// Periksa status HTTP (200 OK)
if ($httpCode != 200) {
    echo "HTTP Error Code: " . $httpCode . "\n";
    echo "Headers: " . $headers . "\n";
    echo "Body: " . $body . "\n";
    exit;
}

// Jika Content-Type bukan JSON, tampilkan respons
if (strpos($headers, "application/json") === false) {
    echo "Unexpected Content-Type. Response headers:\n";
    echo $headers;
    echo "Response body:\n";
    echo $body;
    exit;
}

// Pastikan data dalam format UTF-8, coba lakukan encoding jika perlu
$body = mb_convert_encoding($body, 'UTF-8', 'UTF-8');

// Coba mendekode JSON
$json = json_decode($body, true);

// Periksa jika ada error saat decoding JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Decode Error: " . json_last_error_msg() . "\n";
    echo "Raw response:\n";
    echo $body;
    exit;
}

// Tampilkan hasil JSON jika berhasil
echo "Decoded JSON Data:\n";
print_r($json);

?>

<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$aduan = \App\Models\Aduan::latest()->first();
if ($aduan) {
    echo 'ID: ' . $aduan->id . PHP_EOL;
    echo 'Encrypted AES Key: ' . ($aduan->encrypted_aes_key ? 'EXISTS' : 'NULL') . PHP_EOL;
    echo 'Nama Pelapor (raw): ' . substr($aduan->nama_pelapor, 0, 20) . '...' . PHP_EOL;
    if ($aduan->encrypted_aes_key) {
        $key = \App\Helpers\RsaHelper::decryptKey($aduan->encrypted_aes_key);
        echo 'Decrypted AES Key length: ' . strlen($key) . PHP_EOL;
        $plain = \App\Helpers\AesHelper::decrypt($aduan->nama_pelapor, $key);
        echo 'Decrypted Nama Pelapor: ' . $plain . PHP_EOL;
    } else {
        $plain = \App\Helpers\AesHelper::decrypt($aduan->nama_pelapor, 'PPKPTith');
        echo 'Decrypted Nama Pelapor (Old key PPKPTith): ' . $plain . PHP_EOL;
        
        // Let's try with default env('KEY_AES')
        $usedKey = hash('sha256', env('KEY_AES', ''));
        $data = base64_decode($aduan->nama_pelapor);
        if (strlen($data) >= 16) {
            $iv = substr($data, 0, 16);
            $cipher = substr($data, 16);
            $plain2 = openssl_decrypt($cipher, 'AES-256-CBC', $usedKey, OPENSSL_RAW_DATA, $iv);
            echo 'Decrypted Nama Pelapor (Old key empty env): ' . $plain2 . PHP_EOL;
        }
    }
} else {
    echo 'No aduan found.' . PHP_EOL;
}

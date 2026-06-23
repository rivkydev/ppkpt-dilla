<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$aesKey = \App\Helpers\AesHelper::generateKey();
echo "AES Key: " . $aesKey . PHP_EOL;
$encryptedData = \App\Helpers\AesHelper::encryptWithKey('Test Data 123', $aesKey);
echo "Encrypted: " . $encryptedData . PHP_EOL;
$encryptedAesKey = \App\Helpers\RsaHelper::encryptKey($aesKey);
$decryptedAesKey = \App\Helpers\RsaHelper::decryptKey($encryptedAesKey);
echo "Decrypted AES Key: " . $decryptedAesKey . PHP_EOL;
$decryptedData = \App\Helpers\AesHelper::decryptWithKey($encryptedData, $decryptedAesKey);
echo "Decrypted Data: " . $decryptedData . PHP_EOL;

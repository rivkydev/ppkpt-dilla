<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class RsaHelper
{
    /**
     * Encrypt an AES key using RSA Public Key
     */
    public static function encryptKey($aesKey)
    {
        $publicKeyPath = storage_path('app/public_key.pem');
        
        if (!file_exists($publicKeyPath)) {
            throw new \Exception("Public key not found at: {$publicKeyPath}");
        }

        $publicKey = file_get_contents($publicKeyPath);
        
        $encrypted = '';
        if (openssl_public_encrypt($aesKey, $encrypted, $publicKey)) {
            return base64_encode($encrypted);
        }

        throw new \Exception("RSA Encryption failed.");
    }

    /**
     * Decrypt an AES key using RSA Private Key
     */
    public static function decryptKey($encryptedAesKeyBase64)
    {
        $privateKeyPath = storage_path('app/private_key.pem');
        
        if (!file_exists($privateKeyPath)) {
            throw new \Exception("Private key not found at: {$privateKeyPath}");
        }

        $privateKey = file_get_contents($privateKeyPath);
        $encryptedAesKey = base64_decode($encryptedAesKeyBase64);
        
        $decrypted = '';
        if (openssl_private_decrypt($encryptedAesKey, $decrypted, $privateKey)) {
            return $decrypted;
        }

        throw new \Exception("RSA Decryption failed.");
    }
}

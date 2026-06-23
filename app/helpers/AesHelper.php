<?php

namespace App\Helpers;

class AesHelper
{
    /**
     * Generate a random 256-bit (32 bytes) key for AES-256
     */
    public static function generateKey()
    {
        // 32 bytes = 256 bits
        return bin2hex(random_bytes(16)); // 32 hex chars
    }

    /**
     * Encrypt data with a specific key
     */
    public static function encryptWithKey($plain, $key)
    {
        if ($plain === null) return null;

        $iv = random_bytes(16);
        $hashedKey = hash('sha256', $key, true);

        $cipher = openssl_encrypt(
            $plain,
            'AES-256-CBC',
            $hashedKey,
            OPENSSL_RAW_DATA,
            $iv
        );

        return base64_encode($iv . $cipher);
    }

    /**
     * Decrypt data with a specific key
     */
    public static function decryptWithKey($encrypted, $key)
    {
        if ($encrypted === null) return null;

        $hashedKey = hash('sha256', $key, true);

        $data = base64_decode($encrypted);
        if (strlen($data) < 16) return null;

        $iv = substr($data, 0, 16);
        $cipher = substr($data, 16);

        return openssl_decrypt(
            $cipher,
            'AES-256-CBC',
            $hashedKey,
            OPENSSL_RAW_DATA,
            $iv
        );
    }
    
    // Fallback for old data if needed (optional)
    private static function staticKey()
    {
        return hash('sha256', env('KEY_AES'));
    }

    public static function encrypt($plain)
    {
        if ($plain === null) return null;

        $iv = random_bytes(16);
        $cipher = openssl_encrypt(
            $plain,
            'AES-256-CBC',
            self::staticKey(),
            OPENSSL_RAW_DATA,
            $iv
        );

        return base64_encode($iv . $cipher);
    }

    public static function decrypt($encrypted, $key = null)
    {
        if ($encrypted === null) return null;

        if ($key && strlen($key) === 32 && ctype_xdigit($key)) {
            // It's a new dynamic key
            return self::decryptWithKey($encrypted, $key);
        }

        $usedKey = $key ? hash('sha256', $key) : self::staticKey();

        $data = base64_decode($encrypted);
        if (strlen($data) < 16) return null;

        $iv = substr($data, 0, 16);
        $cipher = substr($data, 16);

        return openssl_decrypt(
            $cipher,
            'AES-256-CBC',
            $usedKey,
            OPENSSL_RAW_DATA,
            $iv
        );
    }
}
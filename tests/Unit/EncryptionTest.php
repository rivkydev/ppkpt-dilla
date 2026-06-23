<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\AesHelper;
use App\Helpers\RsaHelper;

class EncryptionTest extends TestCase
{
    /**
     * Test AES-256 Encryption and Decryption
     */
    public function test_aes_encryption_decryption(): void
    {
        $plainText = 'Sangat Rahasia';
        $key = AesHelper::generateKey();
        
        // Encrypt
        $cipherText = AesHelper::encryptWithKey($plainText, $key);
        
        $this->assertNotEquals($plainText, $cipherText);
        $this->assertNotEmpty($cipherText);
        
        // Decrypt
        $decryptedText = AesHelper::decrypt($cipherText, $key);
        
        $this->assertEquals($plainText, $decryptedText);
    }

    /**
     * Test RSA Encryption and Decryption for the AES Key
     */
    public function test_rsa_key_encryption(): void
    {
        $aesKey = AesHelper::generateKey();
        
        // Encrypt the AES key with RSA Public Key
        $encryptedAesKey = RsaHelper::encryptKey($aesKey);
        
        $this->assertNotEquals($aesKey, $encryptedAesKey);
        $this->assertNotEmpty($encryptedAesKey);
        
        // Decrypt the AES key with RSA Private Key
        $decryptedAesKey = RsaHelper::decryptKey($encryptedAesKey);
        
        $this->assertEquals($aesKey, $decryptedAesKey);
    }
}

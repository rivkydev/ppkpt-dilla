<?php

namespace Tests\Unit\Kriptografi;

use Tests\TestCase;
use App\Helpers\RsaHelper;
use App\Helpers\AesHelper;
use Illuminate\Support\Facades\Storage;

class RsaHelperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure RSA keys exist or generate them
        if (!file_exists(storage_path('app/private_key.pem')) || !file_exists(storage_path('app/public_key.pem'))) {
            RsaHelper::generateKeys();
        }
    }

    /** @test */
    public function encrypt_aes_key_with_public_key()
    {
        $aesKey = AesHelper::generateKey();
        $encryptedKey = RsaHelper::encryptKey($aesKey);
        
        $this->assertNotEmpty($encryptedKey);
        $this->assertNotEquals($aesKey, $encryptedKey);
    }

    /** @test */
    public function decrypt_aes_key_with_private_key()
    {
        $aesKey = AesHelper::generateKey();
        $encryptedKey = RsaHelper::encryptKey($aesKey);
        
        $decryptedKey = RsaHelper::decryptKey($encryptedKey);
        
        $this->assertEquals($aesKey, $decryptedKey);
    }

    /** @test */
    public function encrypt_with_missing_public_key()
    {
        // Temporarily move the public key to simulate missing
        $pubPath = storage_path('app/public_key.pem');
        $tempPath = storage_path('app/public_key.pem.bak');
        rename($pubPath, $tempPath);
        
        try {
            RsaHelper::encryptKey(AesHelper::generateKey());
            $this->fail('Expected exception was not thrown');
        } catch (\Exception $e) {
            $this->assertStringContainsString('Public key', $e->getMessage());
        } finally {
            // Restore
            rename($tempPath, $pubPath);
        }
    }

    /** @test */
    public function decrypt_with_wrong_private_key()
    {
        $aesKey = AesHelper::generateKey();
        $encryptedKey = RsaHelper::encryptKey($aesKey);
        
        // Generate a new temporary keypair
        $tempPrivate = storage_path('app/temp_private_key.pem');
        $tempPublic = storage_path('app/temp_public_key.pem');
        
        $config = [
            "digest_alg" => "sha512",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];
        $res = openssl_pkey_new($config);
        
        if (!$res) {
            $this->markTestSkipped('openssl_pkey_new failed. Probably missing openssl.cnf on Windows.');
        }

        openssl_pkey_export($res, $privKey);
        file_put_contents($tempPrivate, $privKey);
        
        // Replace real private key temporarily
        $realPrivate = storage_path('app/private_key.pem');
        $bakPrivate = storage_path('app/private_key.pem.bak');
        rename($realPrivate, $bakPrivate);
        rename($tempPrivate, $realPrivate);
        
        try {
            $decrypted = RsaHelper::decryptKey($encryptedKey);
            $this->assertFalse($decrypted);
        } catch (\Exception $e) {
            $this->assertNotEmpty($e->getMessage());
        } finally {
            rename($realPrivate, $tempPrivate);
            rename($bakPrivate, $realPrivate);
            @unlink($tempPrivate);
            @unlink($tempPublic);
        }
    }

    /** @test */
    public function encrypted_key_length_is_valid_rsa2048()
    {
        $aesKey = AesHelper::generateKey();
        $encryptedKey = RsaHelper::encryptKey($aesKey);
        
        // RSA 2048 bit generates 256 bytes cipher, base64 encoded is ~344 chars
        $this->assertGreaterThan(300, strlen($encryptedKey));
    }

    /** @test */
    public function decrypt_corrupted_rsa_cipher_fails()
    {
        $aesKey = AesHelper::generateKey();
        $encryptedKey = RsaHelper::encryptKey($aesKey);
        
        $corruptedKey = substr_replace($encryptedKey, 'X', -1);
        
        try {
            $decrypted = RsaHelper::decryptKey($corruptedKey);
            $this->assertFalse($decrypted);
        } catch (\Exception $e) {
            $this->assertNotEmpty($e->getMessage());
        }
    }

    /** @test */
    public function hybrid_encryption_flow_works_seamlessly()
    {
        $plaintext = "Data sangat rahasia";
        
        // 1. Generate AES
        $aesKey = AesHelper::generateKey();
        
        // 2. Encrypt Data with AES
        $ciphertext = AesHelper::encryptWithKey($plaintext, $aesKey);
        
        // 3. Encrypt AES Key with RSA
        $encryptedAesKey = RsaHelper::encryptKey($aesKey);
        
        // --- Transport --- //
        
        // 4. Decrypt AES Key with RSA
        $decryptedAesKey = RsaHelper::decryptKey($encryptedAesKey);
        
        // 5. Decrypt Data with AES
        $recoveredPlaintext = AesHelper::decryptWithKey($ciphertext, $decryptedAesKey);
        
        $this->assertEquals($plaintext, $recoveredPlaintext);
    }

    /** @test */
    public function generate_rsa_keypair_creates_valid_keys()
    {
        $pubPath = storage_path('app/public_key.pem');
        $privPath = storage_path('app/private_key.pem');
        
        $this->assertFileExists($pubPath);
        $this->assertFileExists($privPath);
        
        $pubContent = file_get_contents($pubPath);
        $this->assertStringContainsString('BEGIN PUBLIC KEY', $pubContent);
    }
}

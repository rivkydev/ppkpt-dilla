<?php

namespace Tests\Unit\Kriptografi;

use PHPUnit\Framework\TestCase;
use App\Helpers\AesHelper;
use Illuminate\Support\Facades\Storage;

class AesHelperTest extends TestCase
{
    /** @test */
    public function encrypt_decrypt_text_returns_original()
    {
        $key = AesHelper::generateKey();
        $plaintext = "Rahasia";
        
        $ciphertext = AesHelper::encryptWithKey($plaintext, $key);
        $this->assertNotEmpty($ciphertext);
        $this->assertNotEquals($plaintext, $ciphertext);
        
        $decrypted = AesHelper::decryptWithKey($ciphertext, $key);
        $this->assertEquals($plaintext, $decrypted);
    }

    /** @test */
    public function generate_key_creates_valid_aes_key()
    {
        $key = AesHelper::generateKey();
        $this->assertNotEmpty($key);
        $this->assertEquals(32, strlen($key)); // 32 hex chars
    }

    /** @test */
    public function encrypt_with_invalid_key_works_because_we_hash_it()
    {
        $key = "invalid_key_not_base64_or_wrong_length";
        $plaintext = "Rahasia";
        
        $ciphertext = AesHelper::encryptWithKey($plaintext, $key);
        $this->assertIsString($ciphertext);
    }

    /** @test */
    public function decrypt_with_wrong_key_returns_false()
    {
        $keyA = AesHelper::generateKey();
        $keyB = AesHelper::generateKey();
        
        $ciphertext = AesHelper::encryptWithKey("Rahasia", $keyA);
        
        $decrypted = AesHelper::decryptWithKey($ciphertext, $keyB);
        $this->assertFalse($decrypted);
    }

    /** @test */
    public function encrypt_file_generates_valid_cipherfile()
    {
        $key = AesHelper::generateKey();
        $fileContent = "This is a dummy PDF content for testing file encryption.";
        
        $encrypted = AesHelper::encryptWithKey($fileContent, $key);
        
        $this->assertNotEmpty($encrypted);
        $this->assertNotEquals($fileContent, $encrypted);
        $this->assertStringNotContainsString("PDF", $encrypted);
    }

    /** @test */
    public function decrypt_file_restores_exact_file_hash()
    {
        $key = AesHelper::generateKey();
        $fileContent = "This is a dummy PDF content for testing file encryption.";
        $originalHash = hash('sha256', $fileContent);
        
        $encrypted = AesHelper::encryptWithKey($fileContent, $key);
        $decrypted = AesHelper::decryptWithKey($encrypted, $key);
        
        $this->assertEquals($originalHash, hash('sha256', $decrypted));
        $this->assertEquals($fileContent, $decrypted);
    }

    /** @test */
    public function encrypt_empty_string_returns_valid_cipher()
    {
        $key = AesHelper::generateKey();
        $plaintext = "";
        
        $ciphertext = AesHelper::encryptWithKey($plaintext, $key);
        $this->assertNotEmpty($ciphertext);
        
        $decrypted = AesHelper::decryptWithKey($ciphertext, $key);
        $this->assertEquals($plaintext, $decrypted);
    }

    /** @test */
    public function encrypt_special_chars_and_unicode()
    {
        $key = AesHelper::generateKey();
        $plaintext = "Aduan kekerasan: 💥🚨. Mahasiswa (مُحَمَّد) melaporkan Rp 50.000,00.";
        
        $ciphertext = AesHelper::encryptWithKey($plaintext, $key);
        $this->assertNotEmpty($ciphertext);
        
        $decrypted = AesHelper::decryptWithKey($ciphertext, $key);
        $this->assertEquals($plaintext, $decrypted);
    }

    /** @test */
    public function decrypt_tampered_ciphertext_fails()
    {
        $key = AesHelper::generateKey();
        $ciphertext = AesHelper::encryptWithKey("Penting", $key);
        
        // Tamper with the ciphertext (change last character)
        $tampered = substr_replace($ciphertext, 'X', -1);
        
        $decrypted = AesHelper::decryptWithKey($tampered, $key);
        $this->assertFalse($decrypted);
    }

    /** @test */
    public function encrypt_decrypt_large_json_payload()
    {
        $key = AesHelper::generateKey();
        
        // Generate a 10KB JSON payload roughly
        $data = array_fill(0, 500, ['id' => rand(1, 1000), 'desc' => 'Large payload test']);
        $json = json_encode($data);
        
        $ciphertext = AesHelper::encryptWithKey($json, $key);
        $this->assertNotEmpty($ciphertext);
        
        $decrypted = AesHelper::decryptWithKey($ciphertext, $key);
        $this->assertEquals($json, $decrypted);
        $this->assertIsArray(json_decode($decrypted, true));
    }
}

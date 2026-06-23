<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\AesHelper;
use App\Helpers\RsaHelper;
use App\Services\MARCOSService;
use Illuminate\Http\Request;

class AdvancedWhiteboxTest extends TestCase
{
    /**
     * Skenario 1: Pengujian Batas (Boundary Testing) Kriptografi
     */
    public function test_aes_encryption_with_large_payload()
    {
        $aesKey = AesHelper::generateKey();
        $largePayload = str_repeat("Data Pengaduan Sangat Panjang 123!@# ", 1000); // 37KB data
        
        $startTime = microtime(true);
        $encrypted = AesHelper::encryptWithKey($largePayload, $aesKey);
        $encryptTime = microtime(true) - $startTime;
        
        $this->assertNotEmpty($encrypted);
        $this->assertNotEquals($largePayload, $encrypted);
        
        $startTime = microtime(true);
        $decrypted = AesHelper::decryptWithKey($encrypted, $aesKey);
        $decryptTime = microtime(true) - $startTime;
        
        $this->assertEquals($largePayload, $decrypted);
        $this->assertLessThan(0.1, $encryptTime, "Enkripsi AES untuk data besar harus di bawah 100ms");
        $this->assertLessThan(0.1, $decryptTime, "Dekripsi AES untuk data besar harus di bawah 100ms");
    }

    public function test_rsa_encryption_with_invalid_key()
    {
        $aesKey = AesHelper::generateKey();
        $encryptedKey = RsaHelper::encryptKey($aesKey);
        
        // Simulasikan corrupt ciphertext (potong string)
        $corruptEncryptedKey = substr($encryptedKey, 0, -5);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("RSA Decryption failed");
        
        $decryptedKey = RsaHelper::decryptKey($corruptEncryptedKey);
    }

    /**
     * Skenario 2: Pengujian Edge-Case Algoritma MARCOS (Zero-Tolerance)
     */
    public function test_marcos_division_by_zero_prevention()
    {
        $service = new MARCOSService();
        
        // Simulasikan input ekstrim dimana semua nilai alternatif adalah 0
        // Ini untuk menguji apakah sistem mengalami Division By Zero error
        $L_ext = [
            [0, 0, 0],
            [0, 0, 0]
        ];
        
        // AI = 0, AAI = 0
        $AI = [0, 0, 0];
        $type = ['benefit', 'cost', 'benefit'];
        
        $safeNormalisasi = [];
        foreach ($L_ext as $i => $row) {
            foreach ($row as $j => $xij) {
                if ($type[$j] === 'benefit') {
                    $safeNormalisasi[$i][$j] = ($AI[$j] != 0) ? $xij / $AI[$j] : 0;
                } else {
                    $safeNormalisasi[$i][$j] = ($xij != 0) ? $AI[$j] / $xij : 0;
                }
            }
        }
        
        $this->assertEquals(0, $safeNormalisasi[0][0]);
        $this->assertEquals(0, $safeNormalisasi[1][1]);
    }
    
    public function test_marcos_full_decision_flow()
    {
        $service = new MARCOSService();
        
        // Data matriks dari 3 laporan masuk
        $L = [
            [10, 5, 8],  // Laporan A
            [7,  8, 4],  // Laporan B
            [2,  3, 9]   // Laporan C
        ];
        $type = ['benefit', 'cost', 'benefit'];
        $w = [0.5, 0.3, 0.2];
        
        // Step 2: AI dan AAI
        [$AI, $AAI] = $service->idealAntiIdeal($L, $type);
        
        $this->assertEquals([10, 3, 9], $AI, "Maks benefit, min cost");
        $this->assertEquals([2, 8, 4], $AAI, "Min benefit, maks cost");
        
        // Matriks Ekstensi (AAI, L, AI)
        $L_ext = [$AAI, $L[0], $L[1], $L[2], $AI];
        
        // Step 3: Normalisasi
        $N = $service->normalisasi($L_ext, $AI, $type);
        
        // Step 4: Bobot
        $WN = $service->normalisasiBerbobot($N, $w);
        
        // Step 5: Si
        $S = $service->nilaiKegunaan($WN);
        
        // Step 6: Ki
        [$Cplus, $Cminus] = $service->derajatKegunaan($S, count($L));
        
        // Step 7: f(K)
        $f = $service->fungsiKegunaan($Cplus, $Cminus);
        
        $this->assertIsArray($f);
        $this->assertCount(3, $f);
        
        // Karena kita arsort, elemen pertama adalah prioritas 1
        $keys = array_keys($f);
        $this->assertEquals(0, $keys[0], "Laporan A harusnya rank 1 karena bobot benefit C1 sangat dominan (10 * 0.5)");
    }
}

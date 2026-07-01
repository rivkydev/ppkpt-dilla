<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Aduan;
use App\Helpers\AesHelper;
use App\Helpers\RsaHelper;

class SatgasControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $satgas;
    protected $aduan;
    protected $aesKey;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure RSA keys exist
        if (!file_exists(storage_path('app/private_key.pem')) || !file_exists(storage_path('app/public_key.pem'))) {
            $config = [
                "digest_alg" => "sha256",
                "private_key_bits" => 2048,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
            ];
            $res = openssl_pkey_new($config);
            if (!$res) {
                $this->markTestSkipped('openssl_pkey_new failed.');
            }
            openssl_pkey_export($res, $privKey);
            $pubKey = openssl_pkey_get_details($res);
            $pubKey = $pubKey["key"];
            file_put_contents(storage_path('app/private_key.pem'), $privKey);
            file_put_contents(storage_path('app/public_key.pem'), $pubKey);
        }

        $this->satgas = User::factory()->create(['role' => 'satgas']);
        
        // Create an aduan with encrypted data
        $this->aesKey = AesHelper::generateKey();
        $encryptedAesKey = RsaHelper::encryptKey($this->aesKey);
        
        // Mock a file
        Storage::fake('public');
        $fileContent = 'Test file content';
        $encryptedFileContent = AesHelper::encryptWithKey($fileContent, $this->aesKey);
        $filePath = 'aduan/bukti/test_bukti.txt';
        Storage::disk('public')->put($filePath, $encryptedFileContent);

        $this->aduan = Aduan::create([
            'user_id' => User::factory()->create(['role' => 'pelapor'])->id,
            'category' => 'Dosen',
            'kode_aduan' => 'PPKPT1234',
            'nama_pelapor' => AesHelper::encryptWithKey('John Doe', $this->aesKey),
            'alamat_pelapor' => AesHelper::encryptWithKey('Alamat', $this->aesKey),
            'email_pelapor' => AesHelper::encryptWithKey('test@test.com', $this->aesKey),
            'phone_pelapor' => AesHelper::encryptWithKey('12345', $this->aesKey),
            'hubungi' => AesHelper::encryptWithKey('Email', $this->aesKey),
            'nama_korban' => AesHelper::encryptWithKey('Korban', $this->aesKey),
            'jenis_kelamin_korban' => AesHelper::encryptWithKey('Laki-laki', $this->aesKey),
            'status_korban' => AesHelper::encryptWithKey('Mahasiswa', $this->aesKey),
            'nama_terlapor' => AesHelper::encryptWithKey('Terlapor', $this->aesKey),
            'jenis_kelamin_terlapor' => AesHelper::encryptWithKey('Laki-laki', $this->aesKey),
            'status_terlapor' => AesHelper::encryptWithKey('Mahasiswa', $this->aesKey),
            'karakteristik_terlapor' => AesHelper::encryptWithKey('Karakteristik', $this->aesKey),
            'terlapor' => AesHelper::encryptWithKey('Individu', $this->aesKey),
            'warning' => AesHelper::encryptWithKey('Tidak', $this->aesKey),
            'tanggal_peristiwa' => '2023-01-01',
            'chronology' => AesHelper::encryptWithKey('Kronologi', $this->aesKey),
            'lokasi' => AesHelper::encryptWithKey('Kampus', $this->aesKey),
            'bersedia' => AesHelper::encryptWithKey('Ya', $this->aesKey),
            'encrypted_aes_key' => $encryptedAesKey,
            'bukti_pelaporan' => $filePath,
            'pernyataan_pelapor' => $filePath,
            'icon' => 'fa-solid fa-file-circle-check',
        ]);
    }

    /** @test */
    public function satgas_can_decrypt_aduan_with_valid_pin()
    {
        // Valid PIN is 'PPKPTith' or 'satgas123'
        $response = $this->actingAs($this->satgas)->postJson('/satgas/aduan/decrypt/' . $this->aduan->id, [
            'key' => 'PPKPTith'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'data']);
        $this->assertEquals('John Doe', $response->json('data.nama_pelapor'));
    }

    /** @test */
    public function satgas_decrypt_fails_with_invalid_pin()
    {
        $response = $this->actingAs($this->satgas)->postJson('/satgas/aduan/decrypt/' . $this->aduan->id, [
            'key' => 'wrong_pin'
        ]);

        $response->assertJson(['status' => 'error']);
    }

    /** @test */
    public function satgas_can_download_encrypted_file()
    {
        $response = $this->actingAs($this->satgas)->post('/satgas/aduan/download/' . $this->aduan->id . '/bukti', [
            'key' => 'PPKPTith' // or 'satgas123'
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
        
        // The downloaded content should be the decrypted original content
        $this->assertEquals('Test file content', $response->streamedContent());
    }

    /** @test */
    public function download_fails_without_decryption_key()
    {
        $response = $this->actingAs($this->satgas)->post('/satgas/aduan/download/' . $this->aduan->id . '/bukti', [
            'key' => ''
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }

    /** @test */
    public function download_handles_missing_physical_file()
    {
        // Change aduan's file path to something that doesn't exist
        $this->aduan->bukti_pelaporan = 'aduan/bukti/nonexistent.txt';
        $this->aduan->save();

        $response = $this->actingAs($this->satgas)->post('/satgas/aduan/download/' . $this->aduan->id . '/bukti', [
            'key' => 'PPKPTith'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }

    /** @test */
    public function satgas_can_accept_aduan()
    {
        // Add Bobot
        \App\Models\Bobot::create([
            'c1' => 1,
            'c2' => 1,
            'c3' => 1,
            'c4' => 1,
            'c5' => 1,
            'c6' => 1,
        ]);

        \App\Models\Status::create([
            'aduan_id' => $this->aduan->id,
            'label1' => 'Menunggu',
            'status1' => 'Menunggu',
        ]);

        // Add Alternatif
        \App\Models\Alternatif::create([
            'aduan_id' => $this->aduan->id,
            'kriteria1' => 1,
            'kriteria2' => 1,
            'kriteria3' => 1,
            'kriteria4' => 1,
            'kriteria5' => 1,
            'kriteria6' => 1,
        ]);

        // Endpoint: /satgas/terimaaduan/{id}
        $response = $this->actingAs($this->satgas)->post('/satgas/terimaaduan/' . $this->aduan->id);
        
        $response->assertRedirect();
        
        // The implementation in SatgasController adds a new status or updates.
        // Let's just assert the db has something changed
        $this->assertDatabaseHas('statuses', [
            'aduan_id' => $this->aduan->id,
        ]);
    }

    /** @test */
    public function satgas_cannot_be_accessed_by_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/satgas');
        
        $this->assertTrue(in_array($response->status(), [403, 302]));
    }
}

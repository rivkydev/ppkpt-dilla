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

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure RSA keys exist
        if (!file_exists(storage_path('app/private_key.pem')) || !file_exists(storage_path('app/public_key.pem'))) {
            RsaHelper::generateKeys();
        }

        $this->user = User::factory()->create(['role' => 'pelapor']);
    }

    protected function getValidAduanPayload(array $overrides = [])
    {
        return array_merge([
            'category' => 'Dosen',
            'alamat_pelapor' => 'Alamat',
            'pernyataan_pelapor' => UploadedFile::fake()->create('pernyataan.pdf', 100, 'application/pdf'),
            'email_pelapor' => 'test@example.com',
            'phone_pelapor' => '081234567890',
            'hubungi' => 'Email',
            'nama_korban' => 'Korban',
            'jenis_kelamin_korban' => 'Laki-laki',
            'status_korban' => 'Mahasiswa',
            'nama_terlapor' => 'Terlapor',
            'jenis_kelamin_terlapor' => 'Laki-laki',
            'status_terlapor' => 'Mahasiswa',
            'karakteristik_terlapor' => 'Karakteristik',
            'terlapor' => 'Individu',
            'warning' => 'Tidak',
            'tanggal_peristiwa' => '2023-01-01',
            'chronology' => 'Kronologi kejadian',
            'bersedia' => 'Ya',
            'prioritas' => 'Tinggi',
            'lokasi' => 'Kampus',
            'nama_pelapor' => 'John Doe',
            'dampak_fisik' => 5,
            'dampak_psikologis' => 5,
            'keseriusan' => 5,
            'berpotensi' => 5,
            'berulang' => 5,
            'kinerja' => 5,
            'hubungan_sosial' => 5,
            'lingkungan' => 5,
        ], $overrides);
    }

    /** @test */
    public function pelapor_can_submit_aduan()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->post('/user', $this->getValidAduanPayload());

        $response->assertRedirect(route('aduan.store'));
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('aduans', 1);
    }

    /** @test */
    public function aduan_data_is_encrypted_in_database()
    {
        Storage::fake('public');
        
        $this->actingAs($this->user)->post('/user', $this->getValidAduanPayload([
            'nama_pelapor' => 'Rahasia Pelapor'
        ]));

        $aduan = Aduan::first();
        
        $this->assertNotNull($aduan);
        $this->assertNotEquals('Rahasia Pelapor', $aduan->nama_pelapor);
        $this->assertNotEmpty($aduan->encrypted_aes_key);
        
        // Decrypt manually
        $aesKey = RsaHelper::decryptKey($aduan->encrypted_aes_key);
        $decryptedNama = AesHelper::decryptWithKey($aduan->nama_pelapor, $aesKey);
        
        $this->assertEquals('Rahasia Pelapor', $decryptedNama);
    }

    /** @test */
    public function file_bukti_is_encrypted_on_disk()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('bukti.pdf', 100, 'application/pdf');

        $this->actingAs($this->user)->post('/user', $this->getValidAduanPayload([
            'bukti_pelaporan' => $file
        ]));

        $aduan = Aduan::first();
        $this->assertNotNull($aduan->bukti_pelaporan);
        
        Storage::disk('public')->assertExists($aduan->bukti_pelaporan);
        
        $content = Storage::disk('public')->get($aduan->bukti_pelaporan);
        
        // Original file was empty or PDF header, encrypted is random bytes
        $this->assertStringNotContainsString('%PDF', $content);
    }

    /** @test */
    public function pelapor_cannot_access_admin_dashboard()
    {
        $response = $this->actingAs($this->user)->get('/admin');
        // Because of the role middleware, it might return 403 or 302
        $this->assertTrue(in_array($response->status(), [403, 302]));
    }

    /** @test */
    public function aes_key_is_encrypted_with_rsa_on_store()
    {
        Storage::fake('public');
        $this->actingAs($this->user)->post('/user', $this->getValidAduanPayload());

        $aduan = Aduan::first();
        $this->assertTrue(strlen($aduan->encrypted_aes_key) > 300);
        $this->assertStringNotContainsString(' ', $aduan->encrypted_aes_key);
    }

    /** @test */
    public function marcos_score_calculated_on_submit()
    {
        Storage::fake('public');
        $this->actingAs($this->user)->post('/user', $this->getValidAduanPayload());

        $aduan = Aduan::first();
        $this->assertDatabaseHas('alternatifs', [
            'aduan_id' => $aduan->id,
            'kriteria2' => 5,
        ]);
    }

    /** @test */
    public function validation_fails_on_empty_required_fields()
    {
        $response = $this->actingAs($this->user)->post('/user', []);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function pelapor_can_view_tracking_status()
    {
        Storage::fake('public');
        // First submit an aduan
        $this->actingAs($this->user)->post('/user', $this->getValidAduanPayload());

        $aduan = Aduan::first();
        
        \App\Models\Investigation::create([
            'aduan_id' => $aduan->id,
            'kode_aduan' => $aduan->kode_aduan,
            'hasil_akhir' => 'Menunggu',
            'catatan' => 'Testing',
            'tanggal' => now(),
            'jenis_kekerasan' => 'Kekerasan Seksual',
            'lokasi_kejadian' => 'Kampus',
            'nama_korban' => 'Korban',
            'status_korban' => 'Mahasiswa',
            'nama_terlapor' => 'Terlapor',
            'status_terlapor' => 'Mahasiswa',
            'status_investigasi' => 'Selesai',
            'tindak_lanjut' => json_encode(['Tindak lanjut 1']),
        ]);
        
        $response = $this->actingAs($this->user)->get('/user/hasilinvestigasi/' . $aduan->kode_aduan);
        
        $response->assertStatus(200);
        $response->assertViewIs('user.hasilinvestigasi');
    }
}

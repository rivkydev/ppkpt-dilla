<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Aduan;
use App\Models\Status;

class AduanFlowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Role-based access and submission flow.
     */
    public function test_pelapor_can_submit_aduan()
    {
        Storage::fake('public');

        // Arrange
        $user = User::factory()->create([
            'role' => 'pelapor',
            'status_verify' => 1, // Already verified
        ]);

        $this->actingAs($user);

        // Act
        $response = $this->post('/user', [
            'nama_pelapor' => 'John Doe',
            'alamat_pelapor' => 'Jl. Merdeka',
            'email_pelapor' => 'john@test.com',
            'phone_pelapor' => '08123456789',
            'hubungi' => 'WhatsApp',
            'nama_korban' => 'Jane Doe',
            'jenis_kelamin_korban' => 'Perempuan',
            'status_korban' => 'Mahasiswa',
            'nama_terlapor' => 'Bad Guy',
            'jenis_kelamin_terlapor' => 'Laki-laki',
            'status_terlapor' => 'Mahasiswa',
            'karakteristik_terlapor' => 'Tinggi, Kurus',
            'terlapor' => 'Tahu',
            'warning' => 'Tidak',
            'tanggal_peristiwa' => '2023-10-10',
            'category' => 'Kekerasan Seksual',
            'chronology' => 'Test chronologi kejadian.',
            'lokasi' => 'Kampus A',
            'bersedia' => 'Ya',
            
            // MARCOS Weights
            'dampak_fisik' => 3,
            'dampak_psikologis' => 4,
            'keseriusan' => 5,
            'berpotensi' => 2,
            'berulang' => 1,
            'kinerja' => 3,
            'hubungan_sosial' => 2,
            'lingkungan' => 1,

            // Files
            'pernyataan_pelapor' => UploadedFile::fake()->create('pernyataan.pdf', 100),
            'bukti_pelaporan' => UploadedFile::fake()->image('bukti.jpg'),
        ]);

        // Assert
        $response->assertRedirect('/user');
        $response->assertSessionHas('success');

        // Check DB
        $this->assertDatabaseCount('aduans', 1);
        $aduan = Aduan::first();
        $this->assertNotNull($aduan->encrypted_aes_key);
        
        // Decrypt to verify it was encrypted
        $aduan->decryptData();
        $this->assertEquals('John Doe', $aduan->nama_pelapor);

        // Check Status created
        $this->assertDatabaseHas('statuses', [
            'aduan_id' => $aduan->id,
            'label1' => 'Menunggu Verifikasi Admin'
        ]);
        
        // Check Alternatif created (MARCOS)
        $this->assertDatabaseHas('alternatifs', [
            'aduan_id' => $aduan->id,
        ]);
    }
}

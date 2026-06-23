<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aduans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_aduan')->unique()->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('nama_pelapor');
            $table->text('alamat_pelapor');
            $table->text('pernyataan_pelapor');
            $table->text('email_pelapor');
            $table->text('phone_pelapor');
            $table->text('hubungi', ['Telepon', 'Email', 'Pesan']);
            $table->text('nama_korban')->nullable();
            $table->text('jenis_kelamin_korban', ['Laki-laki', 'Perempuan']);
            $table->text('alamat_korban')->nullable();
            $table->text('phone_korban')->nullable();
            $table->text('status_korban', ['Pimpinan', 'Dosen', 'Tenaga Pendidik', 'Satpam', 'OB', 'Mahasiswa']);
            $table->text('nama_terlapor')->nullable();
            $table->text('jenis_kelamin_terlapor', ['Laki-laki', 'Perempuan']);
            $table->text('alamat_terlapor')->nullable();
            $table->text('phone_terlapor')->nullable();
            $table->text('status_terlapor', ['Pimpinan', 'Dosen', 'Tenaga Pendidik', 'Satpam', 'OB', 'Mahasiswa']);
            $table->text('karakteristik_terlapor');
            $table->text('terlapor', ['Iya', 'Tidak']);
            $table->text('warning', ['Iya', 'Tidak']);
            $table->text('warning_detail')->nullable();
            $table->text('tanggal_peristiwa');
            $table->text('category', ['Fisik', 'Verbal', 'Seksual', 'Psikologis']);
            $table->text('chronology');
            $table->text('bukti_pelaporan')->nullable();
            $table->text('lokasi');
            $table->text('icon');
            $table->text('bersedia', ['Siap klarifikasi', 'Perlu pendekatan bertahap', 'Fokus perlindungan korban', 'Tidak yakin']);
            $table->text('prioritas')->nullable();
            $table->text('peringkat')->nullable();
            $table->text('nilai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aduans');
    }
};

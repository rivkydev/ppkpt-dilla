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
        Schema::create('investigations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aduan_id')->constrained('aduans')->cascadeOnDelete();
            $table->string('kode_aduan');
            $table->date('tanggal');
            $table->string('jenis_kekerasan');
            $table->string('lokasi_kejadian');
            $table->string('nama_korban');
            $table->string('status_korban');
            $table->string('nama_terlapor');
            $table->string('status_terlapor');
            $table->string('nama_saksi')->nullable();
            $table->text('keterangan_saksi')->nullable();
            $table->json('proses')->nullable();
            $table->text('catatan_proses')->nullable();
            $table->text('kronologi')->nullable();
            $table->text('wawancara_korban')->nullable();
            $table->text('wawancara_terlapor')->nullable();
            $table->text('wawancara_saksi')->nullable();
            $table->text('fakta_terbukti')->nullable();
            $table->text('fakta_tidak_terbukti')->nullable();
            $table->text('file_terbukti')->nullable();
            $table->json('tindak_lanjut')->nullable();
            $table->text('catatan_tindak_lanjut')->nullable();
            $table->string('hasil_akhir')->nullable();
            $table->text('kesimpulan')->nullable();
            $table->string('status_investigasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investigations');
    }
};

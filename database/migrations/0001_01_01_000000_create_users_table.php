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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'pelapor', 'satgas']);
            $table->string('fullname');
            $table->string('profile');
            $table->enum('status_verify', ['0', '1'])->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('nim_nidn')->unique()->nullable();
            $table->enum('status', ['Pimpinan','Dosen','Tenaga Pendidik','Satpam', 'OB','Mahasiswa'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

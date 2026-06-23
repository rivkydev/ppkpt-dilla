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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aduan_id')->constrained('aduans')->cascadeOnDelete();
            $table->string('label1')->nullable();
            $table->string('status1')->nullable();
            $table->string('label2')->nullable();
            $table->string('status2')->nullable();
            $table->string('label3')->nullable();
            $table->string('status3')->nullable();
            $table->string('label4')->nullable();
            $table->string('status4')->nullable();
            $table->string('penolakan')->nullable();
            $table->string('diterima_oleh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};

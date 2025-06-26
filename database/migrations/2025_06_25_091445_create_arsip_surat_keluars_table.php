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
        Schema::create('arsip_surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat', 50)->unique();
            $table->date('tgl_surat');
            $table->string('tujuan', 100);
            $table->string('perihal');
            $table->text('file_surat')->nullable();
            $table->text('qr_code')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_surat_keluars');
    }
};

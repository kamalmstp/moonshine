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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pegawai', 100)->nullable();
            $table->string('nip', 20)->nullable();
            $table->string('jabatan', 50)->nullable();
            $table->string('golongan', 10)->nullable();
            $table->date('tgl_masuk')->nullable();
            $table->enum('status', ['PNS', 'PPPK', 'Honorer Provinsi', 'Honorer Sekolah'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};

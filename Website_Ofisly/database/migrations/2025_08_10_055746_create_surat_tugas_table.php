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
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->uuid('id_surat_tugas')->primary();
            $table->string('no_surat');
            $table->string('nama_kandidat');
            $table->date('tgl_penugasan');
            $table->date('tgl_surat_pembuatan');
            /**
             * Tgl penugasan
             * Tgl surat pembuatan
             * No surat
             */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tugas');
    }
};

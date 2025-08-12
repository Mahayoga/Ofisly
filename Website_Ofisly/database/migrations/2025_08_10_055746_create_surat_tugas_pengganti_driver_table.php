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
        Schema::create('surat_tugas_pengganti_driver', function (Blueprint $table) {
            $table->uuid('id_surat_tugas')->primary();
            // $table->string('no_surat');
            $table->string('nama_kandidat');
            $table->char('nik_kandidat', 16);
            $table->string('jabatan_kandidat');
            $table->string('nama_pengganti_kandidat');
            $table->date('tgl_mulai_penugasan');
            $table->date('tgl_selesai_penugasan');
            $table->date('tgl_surat_pembuatan');
            $table->string('status')->nullable();
            $table->string('created_by')->nullable();
            $table->string('file_path')->nullable();
            /**
             * TANGGALPEMBUATAN .
             * NAMAKANDIDAT .
             * NIKKANDIDAT .
             * JABATANKANDIDAT .
             * PENGGANTIKANDIDAT .
             * TANGGALMULAI .
             * TANGGALSELESAI .
             */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tugas_pengganti_driver');
    }
};

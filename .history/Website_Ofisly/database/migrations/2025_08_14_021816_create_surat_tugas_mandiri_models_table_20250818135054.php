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
        Schema::create('surat_tugas_mandiri', function (Blueprint $table) {
            $table->uuid('id_surat_penempatan')->primary();
            $table->string('nomor_surat');
            $table->date('tgl_surat_pembuatan');
            $table->string('nama_kandidat');
            $table->string('jabatan_kandidat');
            $table->date('tgl_mulai_penempatan');
            $table->string('file_path_docx')->nullable();
            $table->string('file_path_pdf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_penempatan__mandiri');
    }
};

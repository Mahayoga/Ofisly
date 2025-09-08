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
        Schema::create('pendaftar_lowongan', function (Blueprint $table) {
            $table->id();
            $table->uuid('id_lowongan_pekerjaan');
            $table->string('nama');
            $table->string('email');
            $table->string('no_telp')->nullable();
            $table->string('cv');
            $table->enum('status', ['Pending', 'Diterima', 'Ditolak'])->default('Pending');

            $table->timestamps();

            $table->foreign('id_lowongan_pekerjaan')
                ->references('id_lowongan_pekerjaan')
                ->on('lowongan_pekerjaan')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftar');
    }
};

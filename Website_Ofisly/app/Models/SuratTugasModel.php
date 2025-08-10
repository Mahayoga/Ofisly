<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratTugasModel extends Model
{
    protected $table = 'surat_tugas';
    protected $primaryKey = 'id_surat_tugas';
    protected $fillable = [
    'no_surat',
    'nama_kandidat',
    'tgl_penugasan',
    'tgl_surat_pembuatan',
    'status', // jika diperlukan status surat
    'created_by', // user yang membuat
    'file_path' // untuk menyimpan path file yang digenerate
];
}

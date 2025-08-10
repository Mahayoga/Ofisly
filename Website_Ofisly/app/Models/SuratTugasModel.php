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
    ];
}

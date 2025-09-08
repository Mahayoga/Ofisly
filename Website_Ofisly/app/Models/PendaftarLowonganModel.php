<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftarLowonganModel extends Model
{
    use HasFactory;

    protected $table = 'pendaftar';

    protected $fillable = [
        'id_lowongan_pekerjaan',
        'nama',
        'email',
        'no_telp',
        'cv',
        'status',
    ];

    public function lowongan()
    {
        return $this->belongsTo(LowonganPekerjaanModel::class, 'id_lowongan_pekerjaan', 'id_lowongan_pekerjaan');
    }
}

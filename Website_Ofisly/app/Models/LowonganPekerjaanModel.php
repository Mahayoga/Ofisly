<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class LowonganPekerjaanModel extends Model
{
    use HasFactory;

    protected $table = 'lowongan_pekerjaan';

    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'tanggal_post',
    ];

    protected $casts = [
        'tanggal_post' => 'date',
    ];

    // Accessor untuk menampilkan tanggal_post sebagai "x hari yang lalu"
    public function getTanggalPostRelativeAttribute()
    {
        return Carbon::parse($this->tanggal_post)->diffForHumans();
    }
}

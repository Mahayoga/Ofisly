<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LowonganPekerjaanModel extends Model
{
    protected $table = 'lowongan_pekerjaan';

    protected $primaryKey = 'id_lowongan_pekerjaan';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'tanggal_post',
    ];

    protected $casts = [
        'id_lowongan_pekerjaan' => 'string',
        'tanggal_post' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id_lowongan_pekerjaan)) {
                $model->id_lowongan_pekerjaan = Str::uuid();
            }
        });
    }

    public function pendaftar()
    {
        return $this->hasMany(PendaftarLowonganModel::class, 'id_lowongan_pekerjaan');
    }
}

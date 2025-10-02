<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PendaftarLowonganModel extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_lowongan';
    protected $primaryKey = 'id_pendaftar';
    public $incrementing = false;   // karena AUTO_INCREMENT
    protected $keyType = 'string';    // bigint

    protected $fillable = [
        'id_pendaftar',
        'id_lowongan_pekerjaan',
        'nama',
        'email',
        'no_telp',
        'cv',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id_pendaftar)) {
                $model->id_pendaftar = Str::uuid();
            }
        });
    }

    public function lowongan()
    {
        return $this->belongsTo(
            LowonganPekerjaanModel::class,
            'id_lowongan_pekerjaan',
            'id_lowongan_pekerjaan'
        );
    }
}

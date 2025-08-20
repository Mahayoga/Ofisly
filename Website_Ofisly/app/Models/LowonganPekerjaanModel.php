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
        'tgl_post',
    ];

    protected $casts = [
        'id_lowongan_pekerjaan' => 'string',
        'tgl_post' => 'date',

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

    public function getTanggalPostRelativeAttribute()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

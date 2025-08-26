<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SuratTugasPromotor extends Model
{
    //
    protected $table = 'surat_tugas_promotor';
    protected $primaryKey = 'id_surat_tugas_promotor';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_surat_tugas_promotor',
        'tgl_surat_pembuatan',
        'nama_kandidat',
        'penempatan',
        'tgl_penugasan',
        'penempatan'
    ];

    protected $casts = [
        'tgl_surat_pembuatan' => 'date',
        'tgl_penugasan' => 'date',
        'penempatan' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_surat_tugas_promotor)) {
                $model->id_surat_tugas_promotor = Str::uuid();
            }
        });
    }
}

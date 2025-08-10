<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SuratTugasModel extends Model
{
    protected $table = 'surat_tugas';
    protected $primaryKey = 'id_surat_tugas';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_surat_tugas',
        'no_surat',
        'nama_kandidat',
        'tgl_penugasan',
        'tgl_surat_pembuatan',
        'status',
        'created_by',
        'file_path'
    ];

    protected $casts = [
        'tgl_penugasan' => 'date',
        'tgl_surat_pembuatan' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_surat_tugas)) {
                $model->id_surat_tugas = Str::uuid();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

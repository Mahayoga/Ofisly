<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SuratTugasPenggantiDriverModel extends Model
{
    protected $table = 'surat_tugas_pengganti_driver';
    protected $primaryKey = 'id_surat_tugas';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_kandidat',
        'nik_kandidat',
        'jabatan_kandidat',
        'nama_pengganti_kandidat',
        'daerah_penempatan',
        'tgl_mulai_penugasan',
        'tgl_selesai_penugasan',
        'tgl_surat_pembuatan',
        'file_path_docx',
        'file_path_pdf',
        'is_arsip',
    ];

    protected $casts = [
        'id_surat_tugas' => 'string',
        'tgl_mulai_penugasan' => 'date',
        'tgl_selesai_penugasan' => 'date',
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

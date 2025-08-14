<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SuratPenempatanDriverMandiriModel extends Model
{
   protected $table = 'surat_penempatan_driver_mandiri';
    protected $primaryKey = 'id_surat_penempatan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nomor_surat',
        'nama_kandidat',
        'jabatan_kandidat',
        'tgl_mulai_penempatan',
        'file_path_docx',
        'file_path_pdf',
    ];

    protected $casts = [
        'id_surat_penempatan' => 'string',
        'tgl_mulai_penempatan' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_surat_penempatan)) {
                $model->id_surat_penempatan= Str::uuid();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

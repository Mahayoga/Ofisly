<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PendaftarLowonganModel extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_lowongan';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
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
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
        });
    }

    public function lowongan()
    {
        return $this->belongsTo(LowonganPekerjaanModel::class, 'id_lowongan_pekerjaan', 'id_lowongan_pekerjaan');
    }
}

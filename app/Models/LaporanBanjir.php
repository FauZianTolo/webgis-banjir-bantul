<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini

class LaporanBanjir extends Model
{
    use HasFactory, SoftDeletes; // Tambahkan SoftDeletes

    protected $table = 'laporan_banjir';

    protected $fillable = [
        'nama_pelapor',
        'no_telp',
        'latitude',
        'longitude',
        'kecamatan',
        'desa',
        'deskripsi',
        'foto',
        'foto2',
        'foto3',
        'kedalaman_cm',
        'status',
        'waktu_laporan'
    ];

    /**
     * Get all non-null foto as array (max 3)
     */
    public function getFotoArrayAttribute(): array
    {
        return array_filter([$this->foto, $this->foto2, $this->foto3]);
    }

    protected $casts = [
        'waktu_laporan' => 'datetime',
    ];

    // Tambahkan di bagian bawah model

public function user()
{
    return $this->belongsTo(User::class);
}
}

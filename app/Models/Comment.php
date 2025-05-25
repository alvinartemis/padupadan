<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'komentar'; // Nama tabel di database Anda
    protected $primaryKey = 'idKomentar'; // Primary key dari tabel komentar Anda
    public $timestamps = false; // Jika Anda tidak menggunakan kolom `created_at` dan `updated_at`

    protected $fillable = [
        'idKomentar',
        'idVideoFashion',
        'idPengguna',
        'tanggalKomentar',
        'isiKomentar',
    ];

    // Relasi ke model VideoFashion
    public function video()
    {
        return $this->belongsTo(VideoFashion::class, 'idVideoFashion', 'idVideoFashion');
    }

    // Relasi ke model User
    public function user()
    {
        // Sama seperti di VideoFashion, ini mengasumsikan primary key User adalah 'idPengguna'
        return $this->belongsTo(\App\Models\User::class, 'idPengguna', 'idPengguna');
    }
}

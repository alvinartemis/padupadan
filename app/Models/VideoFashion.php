<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoFashion extends Model
{
    use HasFactory;

    protected $table = 'videofashion';
    protected $primaryKey = 'idVideoFashion';

    public $timestamps = false; // <-- PASTIKAN INI TIDAK DIKOMENTARI jika tidak ada timestamps di DB

    protected $fillable = [
        'idPengguna',
        'deskripsi',
        'tag',
        'formatFile',
        'ukuranFile',
        'pathFile',   // <-- TAMBAHKAN INI (jika sudah di-migrasi)
        'mimeType',   // <-- TAMBAHKAN INI (jika sudah di-migrasi)
    ];

    // Relasi ke model User/Pengguna
    public function user()
    {
        return $this->belongsTo(User::class, 'idPengguna', 'idPengguna'); // Sesuaikan 'User::class' dan foreign key
    }
}

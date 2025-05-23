<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoFashion extends Model
{
    use HasFactory;

    protected $table = 'videofashion';
    protected $primaryKey = 'idVideoFashion';

    // --- PASTIKAN BARIS INI TIDAK DIKOMENTARI ---
    public $timestamps = false; // Jika tabel tidak memiliki created_at dan updated_at

    protected $fillable = [
        'idPengguna',
        'deskripsi',
        'tag',
        'formatFile',
        'ukuranFile',
    ];
}

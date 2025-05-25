<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoFashion extends Model
{
    use HasFactory;

    protected $table = 'videofashion';
    protected $primaryKey = 'idVideoFashion';

    public $timestamps = false;

    protected $fillable = [
        'idPengguna',
        'deskripsi',
        'tag',
        'formatFile',
        'ukuranFile',
        'pathFile',
        'mimeType',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'idPengguna', 'idPengguna');
    }

    public function comments()
    {
        // 'idVideoFashion' di sini adalah nama kolom foreign key di tabel 'komentar'
        // yang merujuk ke primary key 'idVideoFashion' di tabel 'videofashion'.
        return $this->hasMany(Comment::class, 'idVideoFashion');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PencarianVideoFashion extends Model
{
    use HasFactory;

    protected $table = 'pencarianvideofashion';
    protected $primaryKey = 'idPencarian';

    public $timestamps = false; // Jika Anda tidak menggunakan created_at dan updated_at

    protected $fillable = [
        'idPengguna',
        'idVideoFashion', // Ini mungkin opsional jika hanya menyimpan kata kunci, tapi jika ada hasil yang spesifik bisa disimpan
        'kataKunci',
        // Tambahkan kolom lain jika ada, seperti 'waktuPencarian'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idPengguna', 'idPengguna');
    }

    // Jika idVideoFashion bisa null atau merujuk ke video tertentu
    public function videoFashion()
    {
        return $this->belongsTo(VideoFashion::class, 'idVideoFashion', 'idVideoFashion');
    }
}

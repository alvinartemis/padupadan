<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasFactory;

    protected $table = 'pesan';
    protected $primaryKey = 'idPesan';
    public $timestamps = false; // Asumsi tidak ada timestamps

    protected $fillable = [
        'idPengguna',
        'idStylist',
        'isiPesan',
        'lampiranPesan',
        'waktukirim',
        'statusBacaPengguna',
        'statusBacaStylist',
    ];

    protected $casts = [
        'waktukirim' => 'datetime', // Untuk memudahkan manipulasi waktu
    ];

    // Relasi dengan model Pengguna (User)
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'idPengguna', 'idPengguna');
    }

    // Relasi dengan model Stylist
    public function stylist()
    {
        return $this->belongsTo(Stylist::class, 'idStylist', 'idStylist');
    }
}

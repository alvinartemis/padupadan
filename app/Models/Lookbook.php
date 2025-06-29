<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lookbook extends Model
{
    use HasFactory; // Pastikan ini ada jika menggunakan factory

    protected $table = 'lookbook'; // <<< Diperbarui agar sesuai dengan tabel singular Anda
    protected $primaryKey = 'idLookbook'; // PASTIKAN INI ADA DAN BENAR
    public $incrementing = true; // Biasanya true untuk primary key
    public $timestamps = true; // Biasanya true jika ada created_at dan updated_at

    protected $fillable = [
        'idStylist',
        'nama',
        'description',
        'kataKunci',
        'imgLookbook',
    ];

    // Relasi ke Stylist
    public function stylist()
    {
        // Sesuaikan foreign key 'idStylist' di tabel lookbook
        // dan local key 'idStylist' di tabel stylists (primary key Stylist)
        return $this->belongsTo(Stylist::class, 'idStylist', 'idStylist');
    }
}

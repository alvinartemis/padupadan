<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lookbook extends Model
{
    use HasFactory;

    protected $table = 'lookbooks'; // Sesuaikan dengan nama tabel lookbook Anda
    protected $primaryKey = 'idLookbook'; // Sesuaikan primary key lookbook Anda
    public $timestamps = true; // Sesuaikan jika tidak pakai timestamps

    protected $fillable = [
        'stylist_id', // Foreign key ke stylist
        'title',
        'image_path', // Kolom untuk menyimpan path gambar lookbook
        // Tambahkan kolom lain yang relevan seperti description, dll.
    ];

    // Relasi balik ke Stylist (jika diperlukan)
    public function stylist()
    {
        return $this->belongsTo(Stylist::class, 'stylist_id', 'idStylist');
    }
}

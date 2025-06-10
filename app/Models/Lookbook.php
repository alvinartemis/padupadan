<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lookbook extends Model
{
    use HasFactory;

    protected $table = 'lookbook';
    protected $primaryKey = 'idLookbook';
    public $timestamps = true; // <-- PERBAIKAN: Ini harus TRUE jika Anda menambahkan created_at/updated_at di tabel

    protected $fillable = [
        'idStylist',
        'nama',
        'description',
        'kataKunci',
        'imgLookbook',
    ];

    public function stylist()
    {
        // Asumsi primary key di tabel 'stylist' adalah 'idStylist'
        // dan Anda punya model 'Stylist'
        return $this->belongsTo(Stylist::class, 'idStylist', 'idStylist'); // <-- PASTIKAN INI

        // Atau jika model Stylist Anda tidak ada, dan Anda hanya merujuk ke ID dari tabel 'stylist'
        // tanpa model terpisah, Anda mungkin tidak perlu relasi ini di Lookbook.
    }
}

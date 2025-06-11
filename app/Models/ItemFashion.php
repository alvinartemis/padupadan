<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemFashion extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini (Laravel secara default menganggap 'item_fashions')
    protected $table = 'item_fashions';

    // Kolom-kolom yang dapat diisi secara massal (mass assignable)
    // Ini penting agar Anda bisa menggunakan ItemFashion::create() atau mengisi secara langsung
    protected $fillable = [
        'user_id',
        'lookbook_id',
    ];

    /**
     * Get the user that owns the ItemFashion.
     * Mendefinisikan relasi "many-to-one" ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lookbook that the ItemFashion belongs to.
     * Mendefinisikan relasi "many-to-one" ke model Lookbook.
     * Pastikan foreign key dan local key sesuai dengan skema database Anda.
     * 'lookbook_id' adalah foreign key di tabel item_fashions
     * 'idLookbook' adalah primary key di tabel lookbooks
     */
    public function lookbook()
    {
        return $this->belongsTo(Lookbook::class, 'lookbook_id', 'idLookbook');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Stylist extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'stylist';
    protected $primaryKey = 'idStylist';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'username',
        'email',
        'profilepicture',
        'speciality',
        'job',
        'location',
        'gender',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function pesanMasuk()
    {
        return $this->hasMany(Pesan::class, 'idStylist', 'idStylist');
    }

    public function lookbooks()
    {
        // Ganti 'stylist_id' dengan nama kolom foreign key di tabel 'lookbooks'
        // yang merujuk ke 'idStylist' di tabel 'stylist' Anda.
        // Ganti 'lookbooks_table_name' jika nama tabel lookbook Anda berbeda
        return $this->hasMany(Lookbook::class, 'idStylist', 'idStylist'); // Asumsi 'idStylist' di tabel lookbook adalah FK
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Stylist extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'stylist'; // sesuaikan dengan nama tabel sebenarnya
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
        return 'username'; // agar Laravel tahu field username yang digunakan
    }

}

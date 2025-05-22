<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengguna'; // Ubah nama tabel menjadi 'pengguna'

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'idPengguna'; // Ubah primary key menjadi 'idPengguna'

    /**
     * Indicates if the primary key is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true; // Sesuaikan jika 'idPengguna' Anda auto-increment

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'username',
        'email',
        // 'profilepicture',
        'password',
        // 'gender',
        // 'bodytype',
        // 'skintone',
        // 'style',
        // 'preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'preferences' => 'array', // Jika preferences Anda disimpan sebagai array JSON
    ];

    /**
     * Get the name of the "username" column.
     *
     * @return string
     */
    public function username()
    {
        return 'username'; // Memberitahu Laravel untuk menggunakan kolom 'username' saat login
    }
}

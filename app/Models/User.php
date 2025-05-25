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
    protected $table = 'pengguna';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'idPengguna';

    /**
     * Indicates if the primary key is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

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
        'gender',
        'bodytype',
        'skintone',
        'style',
        'preferences',
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
        'preferences' => 'array',
    ];

    /**
     * Get the name of the "username" column.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    public function chatsWithStylists()
    {
        return $this->belongsToMany(Stylist::class, 'pesan', 'idPengguna', 'idStylist')
                    ->select('stylist.idStylist')
                    ->withTimestamps('waktukirim');
    }

    public function pesanKirim()
    {
        return $this->hasMany(Pesan::class, 'idPengguna', 'idPengguna');
    }
}

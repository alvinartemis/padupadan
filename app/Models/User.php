<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Aktifkan jika Anda menggunakan verifikasi email
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Jika menggunakan Sanctum untuk API

class User extends Authenticatable // Implementasikan MustVerifyEmail jika perlu
{
    use HasApiTokens, HasFactory, Notifiable; // Tambahkan trait lain jika perlu

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'pengguna';

    /**
     * Primary key untuk model.
     *
     * @var string
     */
    protected $primaryKey = 'idPengguna';

    /**
     * Menunjukkan apakah primary key adalah auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Menunjukkan apakah model harus menggunakan timestamps (created_at, updated_at).
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'username',
        'email',
        'profilepicture',
        'password',
        'gender',
        'bodytype',
        'skintone',
        'style',
        'preferences',
        'bio',
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang harus di-cast ke tipe native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'preferences' => 'array',
        'password' => 'hashed',
    ];

    /**
     * Get the name of the "username" column for authentication.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    public function videoFashions()
    {
        return $this->hasMany(VideoFashion::class, 'idPengguna', 'idPengguna');
    }

    public function chatsWithStylists()
    {
        return $this->belongsToMany(Stylist::class, 'pesan', 'idPengguna', 'idStylist')
                    ->withTimestamps();
    }

    public function koleksiPakaian()
    {
        return $this->hasMany(KoleksiPakaian::class, 'idPengguna', 'idPengguna');
    }

    public function outfits()
    {
        return $this->hasMany(Outfit::class, 'id_pengguna', 'idPengguna');
    }

    public function pesanKirim()
    {
        return $this->hasMany(Pesan::class, 'idPengguna', 'idPengguna');
    }
}

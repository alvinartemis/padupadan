<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Aktifkan jika Anda menggunakan verifikasi email
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Jika menggunakan Sanctum untuk API

// Import model-model terkait yang digunakan dalam relasi
use App\Models\VideoFashion;
use App\Models\Stylist; // Asumsi ada model Stylist
use App\Models\KoleksiPakaian; // Asumsi ada model KoleksiPakaian
use App\Models\Outfit; // Asumsi ada model Outfit
use App\Models\Pesan; // Asumsi ada model Pesan

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
    public $timestamps = false; // Penting: Jika tabel 'pengguna' Anda memiliki kolom 'created_at' dan 'updated_at',
                                // dan Anda ingin Laravel mengelolanya secara otomatis, ubah ini menjadi `true`.
                                // Jika `false`, Laravel tidak akan mengisi atau memperbarui kolom tersebut.

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'username',
        'email',
        'profilepicture', // Sudah ada dan ini benar untuk upload foto profil
        'password',
        'gender',
        'bodytype',
        'skintone',
        'style',
        'preferences',
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
        'password' => 'hashed', // Penting: Memastikan password di-hash secara otomatis saat diset
    ];

    /**
     * Get the name of the "username" column for authentication.
     * Ini digunakan oleh Laravel Auth jika Anda menggunakan 'username' sebagai pengganti 'email' untuk login.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Definisi relasi One-to-Many dengan model VideoFashion.
     * Asumsi 'idPengguna' adalah foreign key di tabel 'video_fashions' yang merujuk ke 'idPengguna' di tabel 'pengguna'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videoFashions()
    {
        return $this->hasMany(VideoFashion::class, 'idPengguna', 'idPengguna');
    }

    /**
     * Definisi relasi Many-to-Many dengan model Stylist melalui tabel pivot 'pesan'.
     * Asumsi 'idPengguna' dan 'idStylist' adalah foreign key di tabel 'pesan'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function chatsWithStylists()
    {
        return $this->belongsToMany(Stylist::class, 'pesan', 'idPengguna', 'idStylist')
                    ->withTimestamps();
    }

    /**
     * Definisi relasi One-to-Many dengan model KoleksiPakaian.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function koleksiPakaian()
    {
        return $this->hasMany(KoleksiPakaian::class, 'idPengguna', 'idPengguna');
    }

    /**
     * Definisi relasi One-to-Many dengan model Outfit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outfits()
    {
        return $this->hasMany(Outfit::class, 'id_pengguna', 'idPengguna');
    }

    /**
     * Definisi relasi One-to-Many dengan model Pesan (pesan yang dikirim oleh pengguna ini).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pesanKirim()
    {
        return $this->hasMany(Pesan::class, 'idPengguna', 'idPengguna');
    }
}

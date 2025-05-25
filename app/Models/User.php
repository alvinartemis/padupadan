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
        'profilepicture', // Tambahkan ini jika Anda akan mengelola upload gambar profil melalui form edit
        'password',
        'gender',
        'bodytype',
        'skintone',
        'style',
        'preferences',
        'bio', // Tambahkan 'bio' jika ada kolom bio di tabel dan ingin diupdate
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token', // Umumnya ada jika menggunakan fitur "remember me" bawaan Laravel
    ];

    /**
     * Atribut yang harus di-cast ke tipe native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // Jika Anda memiliki kolom ini dan menggunakan verifikasi email
        'preferences' => 'array',        // Untuk menyimpan data JSON sebagai array PHP
        'password' => 'hashed',          // Untuk otomatis hashing password saat diset/diupdate
    ];

    /**
     * Get the name of the "username" column for authentication.
     *
     * @return string
     */
    public function username()
    {
        return 'username'; // Kolom yang digunakan untuk login (selain email)
    }

    // Relasi ke VideoFashion (jika Anda ingin mengambil postingan user dari model User)
    public function videoFashions()
    {
        // Sesuaikan 'idPengguna_foreign' dengan nama kolom foreign key di tabel video_fashions
        // Sesuaikan 'idPengguna_local' dengan nama primary key di tabel pengguna (idPengguna)
        return $this->hasMany(VideoFashion::class, 'idPengguna', 'idPengguna');
    }


    // Relasi yang sudah ada
    public function chatsWithStylists()
    {
        // Asumsi ada model Stylist dan tabel pivot 'pesan'
        // 'waktukirim' pada withTimestamps mungkin perlu dicek, biasanya withTimestamps tidak menerima argumen
        // kecuali jika Anda ingin menamai kolom timestamp di tabel pivot secara kustom.
        // Jika 'waktukirim' adalah kolom data tambahan di tabel pivot, gunakan ->withPivot('waktukirim')
        return $this->belongsToMany(Stylist::class, 'pesan', 'idPengguna', 'idStylist')
                    ->withTimestamps(); // Untuk created_at, updated_at di tabel pivot 'pesan'
                    // ->withPivot('waktukirim'); // Jika 'waktukirim' adalah kolom tambahan di tabel pivot
    }

    public function pesanKirim()
    {
        // Asumsi ada model Pesan
        return $this->hasMany(Pesan::class, 'idPengguna', 'idPengguna');
    }
}

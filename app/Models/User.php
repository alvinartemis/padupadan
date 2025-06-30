<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Lookbook; // Penting: Import model Lookbook

// Import model-model terkait lainnya jika digunakan di relasi lain
use App\Models\VideoFashion;
use App\Models\Stylist;
use App\Models\KoleksiPakaian;
use App\Models\Outfit;
use App\Models\Pesan;
use App\Models\VideoBookmark;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected /* Nama tabel pengguna */ $table = 'pengguna'; // Pastikan nama tabel users Anda adalah 'pengguna'

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected /* Kunci utama model */ $primaryKey = 'idPengguna'; // Pastikan primary key Anda adalah 'idPengguna'

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public /* Penambahan ID otomatis */ $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public /* Stempel waktu model */ $timestamps = false; // Jika tabel Anda memiliki created_at dan updated_at, ubah ini menjadi true

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected /* Atribut yang dapat diisi secara massal */ $fillable = [
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
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected /* Atribut tersembunyi */ $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected /* Atribut yang di-cast */ $casts = [
        'email_verified_at' => 'datetime',
        'preferences' => 'array',
        'password' => 'hashed',
    ];

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public /* Ambil nama pengguna */ function username()
    {
        return 'username';
    }

    /**
     * Get the lookbooks that are bookmarked by the user.
     * Definisi relasi Many-to-Many dengan model Lookbook melalui tabel pivot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function wishlistItems()
    {
        // PERBAIKAN: Menghapus ->withTimestamps() karena tabel pivot 'wishlistitem'
        // Anda tidak memiliki kolom 'created_at' dan 'updated_at'.
        return $this->belongsToMany(
            \App\Models\Lookbook::class,
            'wishlistitem',
            'idPengguna',
            'idLookbook',
            'id',
            'idLookbook'
        )->withPivot('tanggal_ditambahkan');
    }

    // --- Relasi yang sudah ada di model User Anda (tetap dipertahankan) ---

    public /* Relasi video fashion */ function videoFashions()
    {
        return $this->hasMany(VideoFashion::class, 'idPengguna', 'idPengguna');
    }

    public /* Relasi obrolan dengan stylist */ function chatsWithStylists()
    {
        return $this->belongsToMany(Stylist::class, 'pesan', 'idPengguna', 'idStylist')
                    ->withTimestamps();
    }

    public /* Relasi koleksi pakaian */ function koleksiPakaian()
    {
        return $this->hasMany(KoleksiPakaian::class, 'idPengguna', 'idPengguna');
    }

    public /* Relasi pakaian */ function outfits()
    {
        return $this->hasMany(Outfit::class, 'id_pengguna', 'idPengguna');
    }

    public /* Relasi pesan terkirim */ function pesanKirim()
    {
        return $this->hasMany(Pesan::class, 'idPengguna', 'idPengguna');
    }

        public function videoBookmarks()
    {
        return $this->hasMany(VideoBookmark::class, 'idPengguna', 'idPengguna');
    }

        public function __get($key)
    {
        if ($key === 'id') {
            return $this->{$this->primaryKey};
        }
        return parent::__get($key);
    }
}

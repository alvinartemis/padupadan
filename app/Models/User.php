<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Lookbook;
use App\Models\VideoFashion;
use App\Models\Stylist;
use App\Models\KoleksiPakaian;
use App\Models\Outfit;
use App\Models\Pesan;
use App\Models\VideoBookmark;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'pengguna';
    protected $primaryKey = 'idPengguna';
    public $incrementing = true;
    public $timestamps = false;
    protected$fillable = [
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
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function username()
    {
        return 'username';
    }

    public function wishlistItems()
    {
        return $this->belongsToMany(
            \App\Models\Lookbook::class,
            'wishlistitem',
            'idPengguna',
            'idLookbook',
            'id',
            'idLookbook'
        )->withPivot('tanggal_ditambahkan');
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

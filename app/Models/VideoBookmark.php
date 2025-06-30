<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoBookmark extends Model
{
    use HasFactory;

    protected $table = 'video_bookmarks';

    protected $fillable = [
        'idPengguna',
        'idVideoFashion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idPengguna', 'idPengguna');
    }

    public function video()
    {
        return $this->belongsTo(VideoFashion::class, 'idVideoFashion', 'idVideoFashion');
    }
}

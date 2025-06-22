<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lookbook extends Model
{
    use HasFactory;

    protected $table = 'lookbook';
    protected $primaryKey = 'idLookbook';
    public $timestamps = true;

    protected $fillable = [
        'idStylist',
        'nama',
        'description',
        'kataKunci',
        'imgLookbook',
    ];

    public function stylist()
    {
        return $this->belongsTo(Stylist::class, 'idStylist', 'idStylist');
    }
}

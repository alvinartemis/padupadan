<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Make sure you have a User model, or a Pengguna model.
// If your User model represents 'Pengguna' and its primary key is 'idPengguna':
// use App\Models\User; // Or App\Models\Pengguna

class KoleksiPakaian extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Updated to all lowercase as per your table name.
     * @var string
     */
    protected $table = 'koleksipakaian';

    /**
     * The primary key associated with the table.
     * Matches 'idPakaian' from your schema.
     * @var string
     */
    protected $primaryKey = 'idPakaian';

    /**
     * The attributes that are mass assignable.
     * These names must exactly match your database column names.
     *
     * @var array<int, string>
     */

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'section',
        'category',
        'visibility',
        'foto',
        'linkItem', // Matches 'linkItem' from your schema
        'idPengguna', // Matches 'idPengguna' from your schema
    ];

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the model should be timestamped.
     * Your SQL schema for 'koleksipakaian' didn't explicitly list created_at/updated_at.
     * Laravel's Eloquent expects these by default.
     * If your table DOES NOT have `created_at` and `updated_at` columns, set this to false.
     * If it DOES, you can set this to true or remove the line (as true is default).
     */
    //public $timestamps = true; // Adjust if necessary

    /**
     * Get the user (Pengguna) who owns this clothing item.
     */
    public function pengguna()
    {
        // This relationship assumes:
        // 1. You have a User model (e.g., app\Models\User.php) that represents your 'Pengguna' table.
        // 2. The 'Pengguna' table (or your users table) has 'idPengguna' as its primary key.
        // 3. Your User model is configured with: protected $primaryKey = 'idPengguna';

        // The first 'idPengguna' is the foreign key on the 'koleksipakaian' table.
        // The second 'idPengguna' is the primary key on the 'Pengguna' (users) table.
        return $this->belongsTo(User::class, 'idPengguna', 'idPengguna');
        // If your user model is named Pengguna, use Pengguna::class instead of User::class.
    }
}
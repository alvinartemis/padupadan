<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idPengguna')->constrained('pengguna', 'idPengguna')->onDelete('cascade');
            $table->foreignId('idVideoFashion')->constrained('videofashion', 'idVideoFashion')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['idPengguna', 'idVideoFashion']); // Mencegah duplikat
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_bookmarks');
    }
};

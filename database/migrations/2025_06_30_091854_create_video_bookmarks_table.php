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
            $table->integer('idPengguna');
            $table->foreign('idPengguna')
                  ->references('idPengguna')->on('pengguna')
                  ->onDelete('cascade');
            $table->integer('idVideoFashion');
            $table->foreign('idVideoFashion')
                  ->references('idVideoFashion')->on('videofashion')
                  ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['idPengguna', 'idVideoFashion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_bookmarks');
    }
};

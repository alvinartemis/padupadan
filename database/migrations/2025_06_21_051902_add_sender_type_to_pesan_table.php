<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pesan', function (Blueprint $table) {
            // Tambahkan kolom 'sender_type' (string) setelah kolom 'idPengguna'.
            // Nilai bisa 'user' atau 'stylist'.
            // Default ke 'user' atau 'stylist' jika ingin mempermudah data lama.
            // Atau, biarkan nullable dulu untuk mengisi manual data lama.
            $table->string('sender_type')->after('idPengguna')->nullable();
            // Jika Anda ingin memastikan data lama tidak null dan mengisinya dengan logika:
            // $table->string('sender_type')->after('idPengguna')->default('unknown'); // default sementara
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesan', function (Blueprint $table) {
            // Saat rollback migrasi, hapus kolom 'sender_type'.
            $table->dropColumn('sender_type');
        });
    }
};

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
        Schema::table('videofashion', function (Blueprint $table) {
            // Tambahkan kolom 'outfitLink' sebagai string yang bisa null
            $table->string('outfitLink', 255)->nullable()->after('tag'); // Anda bisa menyesuaikan 'after' sesuai kebutuhan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videofashion', function (Blueprint $table) {
            // Hapus kolom 'outfitLink' jika migrasi di-rollback
            $table->dropColumn('outfitLink');
        });
    }
};

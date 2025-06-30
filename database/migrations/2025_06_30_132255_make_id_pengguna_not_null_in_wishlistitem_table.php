<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Pastikan baris ini ada di paling atas

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wishlistitem', function (Blueprint $table) {
            // 1. Hapus foreign key constraint sementara dengan NAMA YANG SESUAI di database Anda.
            // Nama foreign key yang menyebabkan error 'Cannot change column' adalah 'wishlistitem_ibfk_1'.
            $table->dropForeign('wishlistitem_ibfk_1');

            // 2. Hapus semua baris di mana idPengguna adalah NULL
            // Ini penting karena kolom harus bersih dari NULL sebelum diubah menjadi NOT NULL.
            DB::table('wishlistitem')->whereNull('idPengguna')->delete();

            // 3. Ubah kolom idPengguna menjadi NOT NULL
            $table->bigInteger('idPengguna')->unsigned()->nullable(false)->change();

            // 4. Tambahkan kembali foreign key constraint
            // Kita beri nama yang sama agar konsisten dengan yang dihapus.
            $table->foreign('idPengguna')
                  ->references('idPengguna')->on('pengguna')
                  ->onDelete('cascade')->onUpdate('cascade')
                  ->name('wishlistitem_ibfk_1'); // Gunakan nama yang sama saat menghapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishlistitem', function (Blueprint $table) {
            // Hapus foreign key constraint dengan nama yang kita gunakan saat membuatnya di up()
            $table->dropForeign('wishlistitem_ibfk_1');

            // Kembalikan kolom idPengguna menjadi nullable
            $table->bigInteger('idPengguna')->unsigned()->nullable(true)->change();

            // Tambahkan kembali foreign key constraint awal (untuk rollback)
            $table->foreign('idPengguna')
                  ->references('idPengguna')->on('pengguna')
                  ->onDelete('cascade')->onUpdate('cascade')
                  ->name('wishlistitem_ibfk_1'); // Gunakan nama yang sama
        });
    }
};

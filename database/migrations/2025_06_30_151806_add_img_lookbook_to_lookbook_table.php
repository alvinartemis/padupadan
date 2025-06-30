<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('lookbook', function (Blueprint $table) {
            $table->string('imgLookbook')->nullable()->after('kataKunci');
        });
    }

    public function down(): void
    {
        Schema::table('lookbook', function (Blueprint $table) {
            $table->dropColumn('imgLookbook');
        });
    }
};

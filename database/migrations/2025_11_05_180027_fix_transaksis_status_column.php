<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Ubah kolom status menjadi VARCHAR(50)
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('status', 50)->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('status', 20)->default('pending')->change();
        });
    }
};
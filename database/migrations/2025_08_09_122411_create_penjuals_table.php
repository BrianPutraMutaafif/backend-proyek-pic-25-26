<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penjuals', function (Blueprint $table) {
            $table->id();
            // one-to-one: user_id unik
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');

            $table->string('nama')->nullable();
            $table->string('foto_profil')->nullable(); // simpan path
            $table->text('deskripsi')->nullable();
            $table->string('foto_kios')->nullable();   // simpan path
            $table->text('produk')->nullable();        // bisa JSON atau text
            $table->string('lokasi')->nullable();
            $table->string('patokan')->nullable();
            $table->string('kontak')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjuals');
    }
};

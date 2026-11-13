<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiket_kategori', function (Blueprint $table) {
            $table->id('id_tiket');
            $table->string('event_id', 20);
            $table->string('nama_kategori', 100);
            $table->enum('tipe_tier', ['Early Bird', 'Regular', 'VIP']);
            $table->decimal('harga', 10, 2);
            $table->integer('kuota');
            $table->integer('terjual')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('periode_jual', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiket_kategori');
    }
};

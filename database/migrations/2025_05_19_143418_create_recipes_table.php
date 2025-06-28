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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->text('ingredients'); // bahan-bahan
            $table->text('steps');       // langkah-langkah
            $table->integer('duration'); // durasi (menit)
            $table->integer('servings'); // porsi

            // FOREIGN KEY
            $table->foreignId('category_id')
                ->constrained()           // otomatis ke table `categories`, kolom `id`
                ->cascadeOnDelete();      // kalau kategori dihapus, resep juga ikut dihapus

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};

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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->onDelete('cascade'); // [cite: 21, 23, 24]
            $table->string('name'); // [cite: 23]
            $table->string('image_url')->nullable(); // [cite: 23]
            $table->string('rarity'); // [cite: 23]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};

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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // [cite: 29]
            $table->foreignId('card_id')->constrained()->onDelete('cascade'); // [cite: 29]
            $table->decimal('price', 8, 2)->nullable(); // [cite: 30]
            $table->enum('status', ['Collection', 'En vente', 'En échange'])->default('Collection'); // [cite: 31]
            $table->enum('condition', ['Parfait état', 'Comme neuf', 'Bon état', 'Légers défauts', 'Abimé', 'Très abimé']); // [cite: 32]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};

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
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users') // Assure que la table cible est bien 'users'
                ->onDelete('cascade'); // Supprime l'itinéraire si l'utilisateur est supprimé

            $table->string('title');
            $table->string('category');
            $table->unsignedInteger('duration'); // Empêche les valeurs négatives
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};

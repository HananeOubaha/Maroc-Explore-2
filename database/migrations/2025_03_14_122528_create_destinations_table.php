<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('destinations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('itinerary_id')->constrained()->onDelete('cascade'); // Lien vers l'itinéraire
        $table->string('name');  // Nom de la destination
        $table->string('accommodation'); // Lieu de logement
        $table->text('places_to_visit'); // Endroits à visiter
        $table->text('activities'); // Activités à faire
        $table->text('foods_to_try'); // Plats à essayer
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};

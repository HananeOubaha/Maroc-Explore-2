<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Itinerary extends Model
{
    use HasFactory;

    protected $table = 'itineraries'; // Assure que Laravel utilise la bonne table

    protected $primaryKey = 'id'; // Définit la clé primaire (facultatif si 'id' est utilisé par défaut)

    protected $fillable = ['title', 'category', 'duration', 'image', 'user_id'];

    /**
     * Relation : Un itinéraire appartient à un utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function addDestinations(Request $request, $itineraryId)
{
    // Valider les données
    $validated = $request->validate([
        'destinations' => 'required|array|min:2',
        'destinations.*.name' => 'required|string',
        'destinations.*.accommodation' => 'required|string',
        'destinations.*.places_to_visit' => 'required|string',
        'destinations.*.activities' => 'required|string',
        'destinations.*.foods_to_try' => 'required|string',
    ]);

    // Trouver l'itinéraire par ID
    $itinerary = Itinerary::findOrFail($itineraryId);

    // Ajouter les destinations à l'itinéraire
    foreach ($validated['destinations'] as $destinationData) {
        $itinerary->destinations()->create($destinationData);
    }

    return response()->json(['message' => 'Destinations ajoutées avec succès.']);
}
public function destinations()
{
    return $this->hasMany(Destination::class);
}
public function favoritedByUsers()
{
    return $this->hasMany(Favorite::class);
}
}

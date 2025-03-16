<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class ItineraryController extends Controller
{
    /**
     * Afficher tous les itinéraires.
     */
    public function index()
    {
        $itineraries = Itinerary::with('user:id,name')->get();
        return response()->json($itineraries);
    }

    /**
     * Ajouter un itinéraire.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'category' => 'required|string|max:255',
        //     'duration' => 'required|integer|min:1',
        //     'image' => 'nullable|string'
        // ]);

        $itinerary = Itinerary::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'category' => $request->category,
            'duration' => $request->duration,
            'image' => $request->image
        ]);

        return response()->json($itinerary, 201);
    }

    /**
     * Afficher un itinéraire spécifique.
     */
    public function show(Itinerary $itinerary)
    {
        return response()->json($itinerary);
    }

    /**
     * Mettre à jour un itinéraire.
     */
    public function update(Request $request, Itinerary $itinerary)
    {
        Gate::authorize('update', $itinerary); // Vérification avec Policy

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:255',
            'duration' => 'sometimes|integer|min:1',
            'image' => 'nullable|string'
        ]);

        $itinerary->update($request->only(['title', 'category', 'duration', 'image']));

        return response()->json($itinerary);
    }

    /**
     * Supprimer un itinéraire.
     */
    public function destroy(Itinerary $itinerary)
    {
        Gate::authorize('delete', $itinerary); // Vérification avec Policy

        $itinerary->delete();
        return response()->json(['message' => 'Itinerary deleted successfully']);
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

public function getAllItineraries()
{
    $itineraries = Itinerary::with('destinations')->get();

    return response()->json([
        'itineraries' => $itineraries
    ]);
}
public function searchItineraries(Request $request)
{
    $title = $request->query('title'); // Récupère le titre dans l'URL

    if (!$title) {
        return response()->json(['error' => 'Veuillez fournir un titre'], 400);
    }

    $itineraries = Itinerary::where('title', 'LIKE', "%$title%")->get();

    if ($itineraries->isEmpty()) {
        return response()->json(['message' => 'Aucun itinéraire trouvé'], 404);
    }

    return response()->json($itineraries, 200);
}


}

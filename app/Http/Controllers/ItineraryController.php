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
use Illuminate\Http\Request;
use App\Models\Itinerary;

public function searchItineraries(Request $request)
{
    try {
        $query = Itinerary::query();

        if ($request->has('title') && !empty($request->title)) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('destination') && !empty($request->destination)) {
            $query->whereHas('destinations', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->destination . '%');
            });
        }

        $itineraries = $query->with('destinations')->get();

        return response()->json([
            'success' => true,
            'itineraries' => $itineraries
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur interne',
            'error' => $e->getMessage()
        ], 500);
    }
}

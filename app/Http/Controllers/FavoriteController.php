<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->with('itinerary')->get();
        
        return response()->json($favorites);
    }

    public function store($itineraryId)
    {
        $user = Auth::user();

        if (Favorite::where('user_id', $user->id)->where('itinerary_id', $itineraryId)->exists()) {
            return response()->json(['message' => 'Cet itinéraire est déjà dans vos favoris'], 409);
        }

        Favorite::create([
            'user_id' => $user->id,
            'itinerary_id' => $itineraryId,
        ]);

        return response()->json(['message' => 'Itinéraire ajouté aux favoris'], 201);
    }

    public function destroy($itineraryId)
    {
        $user = Auth::user();
        $favorite = Favorite::where('user_id', $user->id)->where('itinerary_id', $itineraryId)->first();

        if (!$favorite) {
            return response()->json(['message' => 'Itinéraire non trouvé dans les favoris'], 404);
        }

        $favorite->delete();

        return response()->json(['message' => 'Itinéraire retiré des favoris'], 200);
    }
}

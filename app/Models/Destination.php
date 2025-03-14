<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'itinerary_id',
        'name',
        'accommodation',
        'places_to_visit',
        'activities',
        'foods_to_try',
    ];

    // Relation avec l'itinéraire
    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
}


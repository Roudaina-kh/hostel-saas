<?php

namespace App\Providers;

use App\Models\Bed;
use App\Models\Extra;
use App\Models\Room;
use App\Models\TentSpace;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Morph Map : définit les alias utilisés dans les colonnes
        // blockable_type (inventory_blocks) et priceable_type (prices)
        // Sécurité : empêche l'injection de noms de classes arbitraires
        // en limitant les valeurs acceptées à celles définies ici
        Relation::enforceMorphMap([
            'room'       => Room::class,
            'bed'        => Bed::class,
            'tent_space' => TentSpace::class,
            'extra'      => Extra::class,
        ]);
    }
}
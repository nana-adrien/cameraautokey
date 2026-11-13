<?php

namespace App\Services;

class DemoFeatures
{
    /**
     * Indique si une fonctionnalité est encore verrouillée dans la version de démonstration.
     */
    public static function isLocked(string $feature): bool
    {
        return in_array($feature, config('demo.locked', []), true);
    }

    /**
     * Libellé lisible d'une fonctionnalité.
     */
    public static function label(string $feature): string
    {
        return config("demo.features.$feature", $feature);
    }
}

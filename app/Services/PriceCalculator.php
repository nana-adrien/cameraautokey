<?php

namespace App\Services;

class PriceCalculator
{
    /**
     * Calcule le prix estimé TTC (en euros) d'une demande d'intervention.
     *
     * @param  array{vehicle_type:string, need:string, key_type:string, desired_key:string, quantity:int, warranty_extension:bool, emergency:bool}  $data
     */
    public function estimate(array $data): int
    {
        $config = config('pricing');

        $unit = ($config['needs'][$data['need']]['base']
            + $config['key_types'][$data['key_type']]['extra']
            + $config['desired_keys'][$data['desired_key']]['extra'])
            * $config['vehicle_types'][$data['vehicle_type']]['multiplier'];

        $quantity = max(1, min(2, (int) $data['quantity']));

        // La 2e clé bénéficie d'une remise.
        $keys = $unit + ($quantity === 2 ? $unit * (1 - $config['second_key_discount']) : 0);

        $total = $keys
            + ($data['warranty_extension'] ? $config['warranty_extension'] * $quantity : 0)
            + ($data['emergency'] ? $config['emergency'] : 0);

        return (int) round($total);
    }
}

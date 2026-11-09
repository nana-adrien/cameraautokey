<?php

namespace App\Support;

class Money
{
    /** Formate un montant en francs CFA : 25000 → « 25 000 FCFA ». */
    public static function fcfa(int|float $amount): string
    {
        return number_format((float) $amount, 0, ',', "\u{202F}").' FCFA';
    }
}

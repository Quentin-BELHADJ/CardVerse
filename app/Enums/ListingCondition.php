<?php

namespace App\Enums;

enum ListingCondition: string
{
    case MINT = 'Parfait état';
    case NEAR_MINT = 'Comme neuf';
    case GOOD = 'Bon état';
    case LIGHT_PLAYED = 'Légers défauts';
    case PLAYED = 'Abimé';
    case POOR = 'Très abimé';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

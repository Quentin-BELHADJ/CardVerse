<?php

namespace App\Enums;

enum ListingStatus: string
{
    case COLLECTION = 'Collection';
    case FOR_SALE = 'En vente';
    case FOR_TRADE = 'En échange';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'card_id', 'price', 'status', 'condition'];

    /**
     * Les conversions de type automatiques (Casting).
     * Mappe les valeurs de la base de données vers des Enums PHP.
     *
     * @var array
     */
    protected $casts = [
        'status' => \App\Enums\ListingStatus::class,
        'condition' => \App\Enums\ListingCondition::class,
    ];

    /**
     * Relation : Un listing appartient à un utilisateur.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Un listing concerne une carte spécifique.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Relation Many-to-Many : Les cartes cibles pour un échange.
     * Représente les cartes que l'utilisateur souhaite recevoir en échange de ce listing.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function targetCards()
    {
        return $this->belongsToMany(Card::class, 'listing_target_cards');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = ['user_id', 'card_id', 'price', 'status', 'condition'];

    protected $casts = [
        'status' => \App\Enums\ListingStatus::class,
        'condition' => \App\Enums\ListingCondition::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function targetCards()
    {
        return $this->belongsToMany(Card::class, 'listing_target_cards');
    }
}

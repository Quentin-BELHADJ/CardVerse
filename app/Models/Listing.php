<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = ['user_id', 'card_id', 'price', 'status', 'condition']; // [cite: 25]
    public function user() { return $this->belongsTo(User::class); }
    public function card() { return $this->belongsTo(Card::class); }
}

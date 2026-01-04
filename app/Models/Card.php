<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['collection_id', 'name', 'image_url', 'rarity']; // [cite: 23]
    public function collection() { return $this->belongsTo(Collection::class); } // [cite: 24]
}

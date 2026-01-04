<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = ['name', 'release_date', 'category']; // [cite: 20]
    public function cards() { return $this->hasMany(Card::class); } // [cite: 21]
}

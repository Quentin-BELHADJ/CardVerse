<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = ['name', 'price', 'description', 'category_id', 'image', 'user_id'];

    public function products(): HasMany
    {
        // On indique qu'une Category est liée à plusieurs Product.
        // Laravel déduira automatiquement que la clé étrangère est 'category_id'
        // dans la table 'products'.
        return $this->hasMany(Product::class);
    }
}

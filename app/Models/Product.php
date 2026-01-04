<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Ces champs peuvent être mis à jour via la méthode Product::update()
     */
    protected $fillable = [
        'name',
        'price',
        'description',
        'category_id',
        'image',
        'user_id'
    ];

    /**
     * Un produit appartient à une catégorie.
     */
    public function category(): BelongsTo
    {
        // On indique qu'un Product appartient à une seule Category.
        // Laravel déduira que la clé étrangère est 'category_id' dans la table 'products'.
        return $this->belongsTo(Category::class);
    }

    /**
     * Un produit appartient à un utilisateur/vendeur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

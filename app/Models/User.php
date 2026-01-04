<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * 'discord_handle' remplace l'ancien 'contact_info' générique.
     * 'is_banned' permet de gérer le bannissement des utilisateurs.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'password', 'role', 'contact_info', 'discord_handle', 'is_banned']; //

    /**
     * Relation : Un utilisateur peut avoir plusieurs listings (cartes dans sa collection).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    /**
     * Les attributs cachés lors de la sérialisation (tableaux/JSON).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les conversions de type automatiques.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Vérifie si l'utilisateur possède le rôle d'administrateur.
     * Utilisé pour les Policies et Gates.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}

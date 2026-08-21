<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Direction extends Model
{
    protected $table = 'directions';

    protected $fillable = [
        'libelle',
        'codeDirection',
        'supprimer',
    ];

    protected function casts(): array
    {
        return [
            'supprimer' => 'boolean',
        ];
    }

    public function cartesLibres(): HasMany
    {
        return $this->hasMany(CarteLibre::class, 'directions_id');
    }
}

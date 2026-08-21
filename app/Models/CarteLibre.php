<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class CarteLibre extends Model
{
    use HasFactory;

    protected $table = 'carteslibres';

    protected $fillable = [
        'directions_id',
        'libelle',
        'capacite',
        'dateDebut',
        'nombreJours',
        'actif',
        'userAdd',
        'userUpdate',
        'userDelete',
        'deleted_at',
        'supprimer',
    ];

    protected function casts(): array
    {
        return [
            'capacite' => 'integer',
            'nombreJours' => 'integer',
            'actif' => 'boolean',
            'supprimer' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public function direction(): BelongsTo
    {
        return $this->belongsTo(Direction::class, 'directions_id');
    }

    public function comptes(): HasMany
    {
        return $this->hasMany(CompteRestau::class, 'carteslibres_id');
    }

    public function setDateDebutAttribute(?string $value): void
    {
        if (blank($value)) {
            $this->attributes['dateDebut'] = null;

            return;
        }

        foreach (['Y-m-d', 'd-m-Y'] as $format) {
            try {
                $this->attributes['dateDebut'] = Carbon::createFromFormat($format, $value)->format('d-m-Y');

                return;
            } catch (\Throwable) {
                // Essayer le format historique suivant.
            }
        }

        $this->attributes['dateDebut'] = $value;
    }

    public function dateDebutPourFormulaire(): ?string
    {
        if (blank($this->dateDebut)) {
            return null;
        }

        foreach (['d-m-Y', 'Y-m-d'] as $format) {
            try {
                return Carbon::createFromFormat($format, $this->dateDebut)->format('Y-m-d');
            } catch (\Throwable) {
                // Essayer le format suivant.
            }
        }

        return null;
    }

    public function dateDebutLisible(): ?string
    {
        $date = $this->dateDebutPourFormulaire();

        return $date ? Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y') : null;
    }

    public function resolveRouteBindingQuery($query, $value, $field = null): Builder
    {
        return parent::resolveRouteBindingQuery($query, $value, $field)
            ->where('supprimer', false);
    }
}

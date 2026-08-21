<?php

namespace App\Http\Requests;

use App\Models\CarteLibre;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCarteLibreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('operateur')?->can('create', CarteLibre::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'libelle' => trim((string) $this->input('libelle')),
            'dateDebut' => $this->filled('dateDebut') ? $this->input('dateDebut') : null,
            'nombreJours' => $this->filled('nombreJours') ? $this->input('nombreJours') : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'directions_id' => [
                'required',
                'integer',
                Rule::exists('directions', 'id')->where('supprimer', 0),
            ],
            'libelle' => [
                'required',
                'string',
                'max:255',
                Rule::unique('carteslibres', 'libelle')->where('supprimer', 0),
            ],
            'capacite' => ['required', 'integer', 'min:1', 'max:1000000'],
            'dateDebut' => ['nullable', 'date_format:Y-m-d'],
            'nombreJours' => ['nullable', 'integer', 'min:1', 'max:3650'],
        ];
    }

    public function messages(): array
    {
        return [
            'directions_id.required' => 'La direction est obligatoire.',
            'directions_id.exists' => "La direction sélectionnée n'est pas disponible.",
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.unique' => 'Une carte libre active utilise déjà ce libellé.',
            'capacite.required' => 'La capacité est obligatoire.',
            'capacite.min' => 'La capacité doit être supérieure ou égale à 1.',
            'capacite.max' => 'La capacité ne doit pas dépasser 1 000 000.',
            'dateDebut.date_format' => 'La date de début est invalide.',
            'nombreJours.min' => 'La durée doit être supérieure ou égale à un jour.',
            'nombreJours.max' => 'La durée ne doit pas dépasser 3 650 jours.',
        ];
    }
}

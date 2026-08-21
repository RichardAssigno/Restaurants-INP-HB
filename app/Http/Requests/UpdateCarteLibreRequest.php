<?php

namespace App\Http\Requests;

use App\Models\CarteLibre;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarteLibreRequest extends FormRequest
{
    public function authorize(): bool
    {
        $carteLibre = $this->route('carteLibre');

        return $carteLibre instanceof CarteLibre
            && ($this->user('operateur')?->can('update', $carteLibre) ?? false);
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
        /** @var CarteLibre $carteLibre */
        $carteLibre = $this->route('carteLibre');

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
                Rule::unique('carteslibres', 'libelle')
                    ->where('supprimer', 0)
                    ->ignore($carteLibre),
            ],
            'capacite' => ['required', 'integer', 'min:1', 'max:1000000'],
            'dateDebut' => ['nullable', 'date_format:Y-m-d'],
            'nombreJours' => ['nullable', 'integer', 'min:1', 'max:3650'],
        ];
    }

    public function messages(): array
    {
        return (new StoreCarteLibreRequest)->messages();
    }
}

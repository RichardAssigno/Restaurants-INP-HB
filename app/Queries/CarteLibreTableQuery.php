<?php

namespace App\Queries;

use App\Models\CarteLibre;
use App\Models\Direction;
use Illuminate\Database\Eloquent\Builder;

class CarteLibreTableQuery
{
    public function handle(array $parameters): array
    {
        $query = CarteLibre::query()
            ->with('direction:id,libelle,codeDirection')
            ->withCount([
                'comptes as comptes_count' => fn ($query) => $query->where('supprimer', false),
                'comptes as comptes_actifs_count' => fn ($query) => $query
                    ->where('supprimer', false)
                    ->where('actif', true),
            ])
            ->where('supprimer', false);

        $total = (clone $query)->count();
        $search = trim((string) data_get($parameters, 'search.value', ''));

        if ($search !== '') {
            $escapedSearch = addcslashes($search, '\\%_');
            $pattern = '%'.$escapedSearch.'%';

            $query->where(function (Builder $query) use ($pattern) {
                $query->where('libelle', 'like', $pattern)
                    ->orWhereHas('direction', function (Builder $query) use ($pattern) {
                        $query->where('libelle', 'like', $pattern)
                            ->orWhere('codeDirection', 'like', $pattern);
                    });
            });
        }

        $filtered = (clone $query)->count();
        $column = (int) data_get($parameters, 'order.0.column', 1);
        $direction = strtolower((string) data_get($parameters, 'order.0.dir', 'asc')) === 'desc'
            ? 'desc'
            : 'asc';

        match ($column) {
            1 => $query->orderBy('libelle', $direction),
            2 => $query->orderBy(
                Direction::select('libelle')->whereColumn('directions.id', 'carteslibres.directions_id'),
                $direction
            ),
            3 => $query->orderBy('capacite', $direction),
            5 => $query->orderBy('comptes_count', $direction),
            6 => $query->orderBy('comptes_actifs_count', $direction),
            default => $query->orderBy('libelle'),
        };

        $start = max(0, (int) ($parameters['start'] ?? 0));
        $requestedLength = (int) ($parameters['length'] ?? 25);
        $length = $requestedLength === -1
            ? min($filtered, 500)
            : min(max($requestedLength, 10), 100);

        $items = $query
            ->orderBy('id')
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(function (CarteLibre $carteLibre) {
                $totalAccounts = (int) $carteLibre->comptes_count;
                $activeAccounts = (int) $carteLibre->comptes_actifs_count;
                $status = match (true) {
                    $totalAccounts === 0 => 'unassigned',
                    $activeAccounts === 0 => 'inactive',
                    $activeAccounts < $totalAccounts => 'partial',
                    default => 'active',
                };

                return [
                    'id' => $carteLibre->id,
                    'libelle' => $carteLibre->libelle,
                    'directions_id' => $carteLibre->directions_id,
                    'direction' => $carteLibre->direction?->libelle,
                    'code_direction' => $carteLibre->direction?->codeDirection,
                    'capacite' => $carteLibre->capacite,
                    'date_debut' => $carteLibre->dateDebutPourFormulaire(),
                    'date_debut_lisible' => $carteLibre->dateDebutLisible(),
                    'nombre_jours' => $carteLibre->nombreJours,
                    'actif' => $activeAccounts > 0,
                    'statut' => $status,
                    'comptes_count' => $totalAccounts,
                    'comptes_actifs_count' => $activeAccounts,
                ];
            });

        return [
            'draw' => (int) ($parameters['draw'] ?? 0),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $items,
        ];
    }
}

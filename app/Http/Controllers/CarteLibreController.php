<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarteLibreRequest;
use App\Http\Requests\UpdateCarteLibreRequest;
use App\Models\CarteLibre;
use App\Models\Direction;
use App\Queries\CarteLibreTableQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CarteLibreController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', CarteLibre::class);

        return view('utilisateurs.cartes-libres.index', [
            'directions' => Direction::query()
                ->where('supprimer', false)
                ->orderBy('libelle')
                ->get(['id', 'libelle', 'codeDirection']),
            'canCreate' => Gate::allows('create', CarteLibre::class),
            'canUpdate' => Gate::allows('update', new CarteLibre),
            'canActivate' => Gate::allows('activate', new CarteLibre),
            'canDelete' => Gate::allows('delete', new CarteLibre),
        ]);
    }

    public function data(Request $request, CarteLibreTableQuery $query): JsonResponse
    {
        Gate::authorize('viewAny', CarteLibre::class);

        return response()->json($query->handle($request->all()));
    }

    public function show(CarteLibre $carteLibre): JsonResponse
    {
        Gate::authorize('view', $carteLibre);

        return response()->json([
            'data' => [
                'id' => $carteLibre->id,
                'directions_id' => $carteLibre->directions_id,
                'libelle' => $carteLibre->libelle,
                'capacite' => $carteLibre->capacite,
                'date_debut' => $carteLibre->dateDebutPourFormulaire(),
                'nombre_jours' => $carteLibre->nombreJours,
                'actif' => $carteLibre->actif,
            ],
        ]);
    }

    public function store(StoreCarteLibreRequest $request): JsonResponse
    {
        $carteLibre = DB::transaction(function () use ($request) {
            return CarteLibre::query()->create([
                ...$request->validated(),
                'actif' => true,
                'supprimer' => false,
                'userAdd' => Auth::guard('operateur')->id(),
            ]);
        });

        return response()->json([
            'message' => 'La carte libre a été créée et activée avec succès.',
            'data' => ['id' => $carteLibre->id],
        ], 201);
    }

    public function update(UpdateCarteLibreRequest $request, CarteLibre $carteLibre): JsonResponse
    {
        DB::transaction(function () use ($request, $carteLibre) {
            $carteLibre->update([
                ...$request->validated(),
                'userUpdate' => Auth::guard('operateur')->id(),
            ]);
        });

        return response()->json(['message' => 'La carte libre a été modifiée avec succès.']);
    }

    public function toggleStatus(CarteLibre $carteLibre): JsonResponse
    {
        Gate::authorize('activate', $carteLibre);

        $active = DB::transaction(function () use ($carteLibre) {
            $lockedCard = CarteLibre::query()
                ->whereKey($carteLibre->id)
                ->where('supprimer', false)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedCard->update([
                'actif' => ! $lockedCard->actif,
                'userUpdate' => Auth::guard('operateur')->id(),
            ]);

            return $lockedCard->actif;
        });

        return response()->json([
            'message' => $active
                ? 'La carte libre a été activée avec succès.'
                : 'La carte libre a été désactivée avec succès.',
            'actif' => $active,
        ]);
    }

    public function destroy(CarteLibre $carteLibre): JsonResponse
    {
        Gate::authorize('delete', $carteLibre);

        $deleted = DB::transaction(function () use ($carteLibre) {
            $lockedCard = CarteLibre::query()
                ->whereKey($carteLibre->id)
                ->where('supprimer', false)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedCard->comptes()->where('supprimer', false)->exists()) {
                return false;
            }

            $lockedCard->update([
                'actif' => false,
                'supprimer' => true,
                'userDelete' => Auth::guard('operateur')->id(),
                'deleted_at' => now(),
            ]);

            return true;
        });

        if (! $deleted) {
            return response()->json([
                'message' => 'Cette carte libre est encore liée à un compte restaurant et ne peut pas être supprimée.',
            ], 422);
        }

        return response()->json(['message' => 'La carte libre a été supprimée avec succès.']);
    }
}

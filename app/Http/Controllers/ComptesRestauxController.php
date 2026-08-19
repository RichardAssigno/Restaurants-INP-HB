<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\CompteRestau;
use App\Models\TypeFacturation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ComptesRestauxController extends Controller
{
    public function index()
    {

        return view('compterestaux.index', [

            'title' => 'Comptes Restaurant',
            'typesfacturations' => TypeFacturation::query()->where('supprimer', 0)->orderBy('libelle', 'asc')->get(),
            'comptesrestaux' => Compte::getComptesRestaux(),

        ]);

    }

    public function ajouter(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'matricule' => ['required', 'string', Rule::exists('etudiants', 'matricule')],
            'typefacturation' => [
                'required',
                Rule::exists('typesfacturations', 'id')->where('supprimer', 0),
            ],
        ], [
            'matricule.required' => 'Le matricule est obligatoire',
            'matricule.string' => 'Le matricule doit être de type chaîne de caractère',
            'typefacturation.required' => 'Le type de facturation est obligatoire',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        return DB::transaction(function () use ($data) {
            $etudiant = DB::table('etudiants')
                ->where('matricule', $data['matricule'])
                ->where('supprimer', 0)
                ->first();

            if (! $etudiant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet etudiant est introuvable ou supprime.',
                ], 422);
            }

            $typeCompteId = DB::table('typescomptes')
                ->where('supprimer', 0)
                ->where(function ($query) {
                    $query->where('codeTypeCompte', 'ET')
                        ->orWhere('libelle', 'ETUDIANT');
                })
                ->value('id');

            if (! $typeCompteId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun type de compte etudiant actif n est configure.',
                ], 422);
            }

            $compte = CompteRestau::query()
                ->where('etudiants_id', $etudiant->id)
                ->where('supprimer', 0)
                ->lockForUpdate()
                ->first();

            $creationCompte = false;

            if (! $compte) {
                $compte = CompteRestau::query()->create([
                    'etudiants_id' => $etudiant->id,
                    'typescomptes_id' => $typeCompteId,
                    'pin' => $this->genererPinUnique(),
                    'capacite' => 1,
                    'solde' => 0,
                    'actif' => 1,
                    'traques' => 0,
                    'userAdd' => Auth::guard('operateur')->id(),
                    'supprimer' => 0,
                ]);

                $creationCompte = true;
            }

            $prixIds = DB::table('prix')
                ->where('supprimer', 0)
                ->pluck('id');

            if ($prixIds->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun prix actif n est configure.',
                ], 422);
            }

            $modifications = 0;

            foreach ($prixIds as $prixId) {
                $facturation = DB::table('facturations')
                    ->where('compterestaux_id', $compte->id)
                    ->where('prix_id', $prixId)
                    ->lockForUpdate()
                    ->first();

                if ($facturation) {
                    if ((int) $facturation->typesFacturations_id !== (int) $data['typefacturation'] || (int) $facturation->supprimer === 1) {
                        DB::table('facturations')
                            ->where('id', $facturation->id)
                            ->update([
                                'typesFacturations_id' => $data['typefacturation'],
                                'supprimer' => 0,
                                'userUpdate' => Auth::guard('operateur')->id(),
                                'updated_at' => now(),
                            ]);

                        $modifications++;
                    }

                    continue;
                }

                DB::table('facturations')->insert([
                    'compterestaux_id' => $compte->id,
                    'prix_id' => $prixId,
                    'typesFacturations_id' => $data['typefacturation'],
                    'userAdd' => Auth::guard('operateur')->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'supprimer' => 0,
                ]);

                $modifications++;
            }

            if (! $creationCompte && $modifications === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce compte restaurant est deja configure avec ce type de facturation.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => $creationCompte
                    ? "Compte restaurant cree avec succes. PIN: {$compte->pin}"
                    : 'Compte restaurant mis a jour avec succes.',
                'pin' => $compte->pin,
            ]);
        });

    }

    public function recuperer($id)
    {
        $compte = $this->detailsCompteRestaurant($id);

        if (! $compte) {
            return response()->json(['message' => 'Compte restaurant introuvable.'], 404);
        }

        return response()->json($compte);
    }

    public function modifier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'compte_id' => ['required', 'integer', 'exists:comptesrestaux,id'],
            'typefacturation' => [
                'required',
                Rule::exists('typesfacturations', 'id')->where('supprimer', 0),
            ],
            'capacite' => ['required', 'integer', 'min:1'],
            'solde' => ['required', 'numeric', 'min:0'],
            'actif' => ['required', 'boolean'],
            'traques' => ['required', 'boolean'],
        ], [
            'typefacturation.exists' => 'Le type de facturation selectionne est invalide.',
            'capacite.min' => 'La capacite doit etre superieure ou egale a 1.',
            'solde.min' => 'Le solde ne peut pas etre negatif.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        DB::transaction(function () use ($data) {
            $compte = CompteRestau::query()
                ->where('supprimer', 0)
                ->lockForUpdate()
                ->findOrFail($data['compte_id']);

            $compte->update([
                'capacite' => $data['capacite'],
                'solde' => $data['solde'],
                'actif' => $data['actif'],
                'traques' => $data['traques'],
                'userUpdate' => Auth::guard('operateur')->id(),
            ]);

            $prixIds = DB::table('prix')
                ->where('supprimer', 0)
                ->pluck('id');

            foreach ($prixIds as $prixId) {
                $facturation = DB::table('facturations')
                    ->where('compterestaux_id', $compte->id)
                    ->where('prix_id', $prixId)
                    ->lockForUpdate()
                    ->first();

                if ($facturation) {
                    DB::table('facturations')
                        ->where('id', $facturation->id)
                        ->update([
                            'typesFacturations_id' => $data['typefacturation'],
                            'supprimer' => 0,
                            'userUpdate' => Auth::guard('operateur')->id(),
                            'updated_at' => now(),
                        ]);

                    continue;
                }

                DB::table('facturations')->insert([
                    'compterestaux_id' => $compte->id,
                    'prix_id' => $prixId,
                    'typesFacturations_id' => $data['typefacturation'],
                    'userAdd' => Auth::guard('operateur')->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'supprimer' => 0,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Compte restaurant modifie avec succes.',
        ]);
    }

    public function supprimer($id)
    {
        DB::transaction(function () use ($id) {
            $compte = CompteRestau::query()
                ->where('supprimer', 0)
                ->lockForUpdate()
                ->findOrFail($id);

            $compte->update([
                'supprimer' => 1,
                'userDelete' => Auth::guard('operateur')->id(),
            ]);

            DB::table('facturations')
                ->where('compterestaux_id', $compte->id)
                ->update([
                    'supprimer' => 1,
                    'userDelete' => Auth::guard('operateur')->id(),
                    'updated_at' => now(),
                ]);
        });

        return response()->json(['message' => 'Compte restaurant supprime avec succes.']);
    }

    private function genererPinUnique(): string
    {
        do {
            $pin = str_pad((string) random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);
        } while (CompteRestau::query()->where('pin', $pin)->exists());

        return $pin;
    }

    private function detailsCompteRestaurant($id)
    {
        $facturationParCompte = DB::table('facturations as f')
            ->where('f.supprimer', 0)
            ->select(
                'f.compterestaux_id',
                DB::raw('MIN(f.typesFacturations_id) as typesFacturations_id')
            )
            ->groupBy('f.compterestaux_id');

        return DB::table('comptesrestaux as cr')
            ->join('etudiants as e', 'e.id', '=', 'cr.etudiants_id')
            ->join('typescomptes as tc', 'tc.id', '=', 'cr.typescomptes_id')
            ->leftJoinSub($facturationParCompte, 'f', function ($join) {
                $join->on('f.compterestaux_id', '=', 'cr.id');
            })
            ->leftJoin('typesfacturations as tf', 'tf.id', '=', 'f.typesFacturations_id')
            ->where('cr.id', $id)
            ->where('cr.supprimer', 0)
            ->select(
                'cr.id as idCompteRestau',
                'cr.pin',
                'cr.capacite',
                'cr.solde',
                'cr.actif',
                'cr.traques',
                'e.matricule',
                'e.nom',
                'e.prenoms',
                'e.telephone',
                'tc.libelle as libelleTypeCompte',
                'tf.id as idTypeFacturation',
                'tf.libelle as libelleTypeFacturation',
                'tf.modeRechargement'
            )
            ->first();
    }
}

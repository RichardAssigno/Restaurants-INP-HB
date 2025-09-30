<?php

namespace App\Http\Controllers;

use App\Events\TransactionUpdated;
use App\Models\CarteLibre;
use App\Models\Compte;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TrimestreAnnee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FacturationController extends Controller
{

    public function index(){

        $services = DB::table('services as s')
            ->whereRaw('? BETWEEN s.debut AND s.fin', [Carbon::now()->format('H:i:s')])
            ->first();

        if (!is_null($services)) {

            return view('facturations.index', [

                "title" => "Facturations",
                "transactionsoperateur" => Compte::getInfosTransactionsTousOperateurs( Carbon::now()->format('H:i:s') ),
                "etudiantfactureparoperateur" => Compte::getEtudiantsOperateurDuJour(Auth::guard("operateur")->id(), Carbon::now()->format('H:i:s') )

            ]);

        }

        return redirect()->back()->with('error', 'Aucun service ouvert actuellement.');

    }

    public function scanqrcode(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string'],

        ], [
            "code.required" => "Le code est obligatoire",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        $compte = Compte::getCompteActifParHeure($data['code'], Carbon::now()->format('H:i:s'));

        $trimestreannee_id = TrimestreAnnee::query()->where('statut', "=", 1)->first()->id;

        $transactionsoperateur = Compte::getInfosTransactionsTousOperateurs( Carbon::now()->format('H:i:s'));

        if (!is_null($compte)) {

            if ($compte->actif == 1){

                if ($compte->traques == 0){

                    if (!is_null($compte->idCarte) && !is_null($compte->libelleDirection)) {

                        $dateDebut = Carbon::parse($compte->dateDebut); // convertit en objet Carbon
                        $aujourdhui = Carbon::now();
                        $dateFin = $dateDebut->copy()->addDays($compte->nombreJours);

                        $comptelibre = Compte::query()->findOrFail($compte->idCompte);

                        if (!($aujourdhui->lt($dateDebut))) {

                            if ($aujourdhui->between($dateDebut, $dateFin)) {

                                $dejadacturer = Transaction::transactioncartelibre($compte->idCompte, $compte->codeService);

                                if (count($dejadacturer) < $compte->capacite) {

                                    $this->insertion($compte, $trimestreannee_id, $transactionsoperateur);

                                    return response()->json([
                                        'success' => true,
                                        'message' => 'Facturation effectuée avec succès',
                                        'transactionsoperateur' => $transactionsoperateur,
                                        'etudiantfactureparoperateur' => Compte::getEtudiantsOperateurDuJour(Auth::guard("operateur")->id(), Carbon::now()->format('H:i:s'))
                                    ]);


                                }

                                return response()->json([
                                    'success' => false,
                                    'message' => 'La limite de facturation pour ce QR Code a été atteinte pour le service en cours.'
                                ], 422);

                            }

                            $comptelibre->update([

                                'actif' => 0,
                                'userUpdate' => Auth::guard("operateur")->id(),

                            ]);

                            return response()->json([
                                'success' => false,
                                'message' => "Le delai d'utilisation de votre carte a été dépassé ."
                            ], 422);

                        }

                        return response()->json([
                            'success' => false,
                            'message' => "Votre carte sera disponible à partir de $compte->dateDebut ."
                        ], 422);

                    }

                    $dejadacturer = Transaction::transactionaujourdhui($compte->idCompte, $compte->codeService);


                    if (is_null($dejadacturer)) {

                        if (mb_strtolower($compte->modeRechargement) === "auto"){

                            $this->insertion($compte, $trimestreannee_id, $transactionsoperateur);

                            return response()->json([
                                'success' => true,
                                'message' => 'Facturation effectuée avec succès',
                                'transactionsoperateur' => $transactionsoperateur,
                                'etudiantfactureparoperateur' => Compte::getEtudiantsOperateurDuJour(Auth::guard("operateur")->id(), Carbon::now()->format('H:i:s'))
                            ]);


                        }

                        $solde = $compte->solde - $compte->valeur;

                        if ($solde >= 0) {

                            $this->insertion($compte, $trimestreannee_id, $transactionsoperateur);

                            $compterestau = Compte::query()->findOrFail($compte->idCompte);

                            $compterestau->update([

                                "solde" => $solde,
                                'userUpdate'=> Auth::guard("operateur")->id(),

                            ]);

                            return response()->json([
                                'success' => true,
                                'message' => 'Facturation effectuée avec succès',
                                'transactionsoperateur' => $transactionsoperateur,
                                'etudiantfactureparoperateur' => Compte::getEtudiantsOperateurDuJour(Auth::guard("operateur")->id(), Carbon::now()->format('H:i:s'))
                            ]);


                        }

                        return response()->json([
                            'success' => false,
                            'message' => 'Votre solde est insuffisant.'
                        ], 422);

                    }

                    return response()->json([
                        'success' => false,
                        'message' => 'Ce compte a déjà été facturé pour ce service.'
                    ], 422);

                }

                return response()->json([
                    'success' => false,
                    'message' => 'Ce compte est traqué. Veillez contacter le service informatique.'
                ], 422);


            }

            return response()->json([
                'success' => false,
                'message' => 'Ce compte n\'est pas actif. Veillez contacter le service informatique.'
            ], 422);

        }

        return response()->json([
            'success' => false,
            'message' => 'Ce compte n\'existe pas. Veillez contacter le service informatique.'
        ], 422);

    }

    private function insertion($compte, $trimestreannee_id, $transactionsoperateur){

        $dataFacture = [
            'comptesrestaux_id' => $compte->idCompte,
            'prix_id' =>  $compte->idPrix,
            'operateurs_id' => Auth::guard("operateur")->id(),
            'trimestresAnnees_id' => $trimestreannee_id,
            'userAdd'=> Auth::guard("operateur")->id(),
        ];

        $dataRefresh = [

            'totalTransaction' => $transactionsoperateur->totalTransaction,
            'libelleService'   => $transactionsoperateur->libelleService,
            'valeur'           => $transactionsoperateur->valeur,

        ];

        Transaction::query()->create($dataFacture);

        broadcast(new TransactionUpdated($dataRefresh));

    }

    public function refresh()
    {

        $heure = now()->format('H:i:s');

        $transactionsoperateur = Compte::getInfosTransactionsTousOperateurs($heure);

        return response()->json([
            'transactionsoperateur' => $transactionsoperateur
        ]);
    }


}

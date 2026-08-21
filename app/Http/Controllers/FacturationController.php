<?php

namespace App\Http\Controllers;

use App\Events\TransactionUpdated;
use App\Models\Compte;
use App\Models\Transaction;
use App\Models\TrimestreAnnee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class FacturationController extends Controller
{
    public function index()
    {
        $service = $this->serviceOuvert();

        if ($service) {

            return view('facturations.index', [

                'title' => 'Facturations',
                "services" => $service,
                'transactionsoperateur' => Compte::getInfosTransactionsTousOperateurs(Auth::guard('operateur')->id(), $service->id),
                'etudiantfactureparoperateur' => Compte::getEtudiantsOperateurDuJour(Auth::guard('operateur')->id(), $service->id),

            ]);

        }

        return redirect()->back()->with('error', 'Aucun service ouvert actuellement.');

    }

    public function scanqrcode(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string'],

        ], [
            'code.required' => 'Le code est obligatoire',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        return $this->traiterScanQrCode($data['code']);
    }

    private function traiterScanQrCode(string $code)
    {
        $code = trim($code);

        $trimestre = TrimestreAnnee::query()->where('statut', 1)->first();

        if (! $trimestre) {
            return response()->json([
                'success' => false,
                'message' => "Aucun trimestre actif n'est configuré.",
            ], 422);
        }

        $service = $this->serviceOuvert();

        if (! $service) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun service ouvert actuellement.',
            ], 422);
        }

        $transactionsoperateur = null;

        $response = DB::transaction(function () use ($code, $trimestre, $service, &$transactionsoperateur) {
            $compte = Compte::getCompteActifParService($code, $service->id);

            if (is_null($compte)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce compte n\'existe pas. Veuillez contacter le service informatique.',
                ], 422);
            }

            if ((int) $compte->actif !== 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce compte n\'est pas actif. Veuillez contacter le service informatique.',
                ], 422);
            }

            if ((int) $compte->traques !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce compte est traqué. Veuillez contacter le service informatique.',
                ], 422);
            }

            if (! is_null($compte->idCarte) ) {
                // Vérifier si la carte n'est pas expiré
                $dateDebut = Carbon::parse($compte->dateDebut);
                $dateFin = $dateDebut->copy()->addDays((int) $compte->nombreJours);
                $aujourdhui = Carbon::now();

                if ($aujourdhui->lt($dateDebut)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Votre carte sera disponible a partir de $compte->dateDebut.",
                    ], 422);
                }


                if (! $aujourdhui->between($dateDebut, $dateFin)) {
                    Compte::query()
                        ->whereKey($compte->idCompte)
                        ->update([
                            'actif' => 0,
                            'userUpdate' => Auth::guard('operateur')->id(),
                        ]);

                    return response()->json([
                        'success' => false,
                        'message' => "Le delai d'utilisation de votre carte a ete depassé.",
                    ], 422);
                }

                if (Transaction::compteNonFacturable($compte->idCompte, $compte->idService)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La limite de facturation pour ce QR Code a été atteinte pour le service en cours.',
                    ], 422);
                }

                $transactionsoperateur = $this->insertion($compte, $trimestre->id);

                return $this->reponseFacturation($transactionsoperateur);
            }

            $dejaFacture = Transaction::transactionaujourdhui($compte->idCompte, $compte->codeService);

            if (! is_null($dejaFacture)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce compte a déjà été facturé pour ce service.',
                ], 422);
            }

            if (mb_strtolower($compte->modeRechargement) !== 'auto') {
                $solde = (int) $compte->solde - (int) $compte->valeur;

                if ($solde < 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Votre solde est insuffisant.',
                    ], 422);
                }

                Compte::query()
                    ->whereKey($compte->idCompte)
                    ->update([
                        'solde' => $solde,
                        'userUpdate' => Auth::guard('operateur')->id(),
                    ]);
            }

            $transactionsoperateur = $this->insertion($compte, $trimestre->id);

            return $this->reponseFacturation($transactionsoperateur);
        }, 3);

        if ($transactionsoperateur) {
            broadcast(new TransactionUpdated($transactionsoperateur));
        }

        return $response;
    }

    private function insertion($compte, $trimestreannee_id)
    {

        $dataFacture = [
            'comptesrestaux_id' => $compte->idCompte,
            'prix_id' => $compte->idPrix,
            'operateurs_id' => Auth::guard('operateur')->id(),
            'trimestresAnnees_id' => $trimestreannee_id,
            'userAdd' => Auth::guard('operateur')->id(),
        ];

        Transaction::query()->create($dataFacture);

        return Compte::getInfosTransactionsTousOperateurs(Auth::guard('operateur')->id(), $compte->idService);

    }

    private function reponseFacturation($transactionsoperateur)
    {
        return response()->json([
            'success' => true,
            'message' => 'Facturation effectuée avec succès',
            'transactionsoperateur' => $transactionsoperateur,
            'etudiantfactureparoperateur' => Compte::getEtudiantsOperateurDuJour(Auth::guard('operateur')->id(), $transactionsoperateur->idService ?? null
            ),
        ]);
    }

    public function refresh()
    {

        $service = $this->serviceOuvert();

        $transactionsoperateur = $service ? Compte::getInfosTransactionsTousOperateurs(Auth::guard('operateur')->id(), $service->id) : null;

        return response()->json([
            'transactionsoperateur' => $transactionsoperateur,
        ]);
    }

    private function serviceOuvert()
    {
        $now = Carbon::now();
        $date = $now->format('d-m-Y');
        $heure = $now->format('H:i');

        $serviceSpecial = DB::table('horairesspeciaux as hs')
            ->join('services as s', 's.id', '=', 'hs.services_id')
            ->join('prix as p', 'p.services_id', '=', 's.id')
            ->where('hs.supprimer', '=', 0)
            ->where('s.supprimer', '=', 0)
            ->where('hs.dateHoraire', $date)
            ->whereRaw(
                "STR_TO_DATE(REPLACE(hs.heureDebut, '.', ':'), '%H:%i') <= STR_TO_DATE(?, '%H:%i')",
                [$heure]
            )
            ->whereRaw(
                "STR_TO_DATE(REPLACE(hs.heureFin, '.', ':'), '%H:%i') >= STR_TO_DATE(?, '%H:%i')",
                [$heure]
            )
            ->select('s.*')
            ->first();

        if ($serviceSpecial) {
            return $serviceSpecial;
        }
        /*public static function getServiceAvecPrix(int $serviceId)
{
    return DB::table('services as s')
        ->join('prix as p', 'p.services_id', '=', 's.id')
        ->where('s.id', $serviceId)
        ->select('s.*', 'p.*')
        ->get();
}*/

        return DB::table('services as s')
            ->join('prix as p', 'p.services_id', '=', 's.id')
            ->where('s.supprimer', '=', 0)
            ->whereRaw('? BETWEEN s.debut AND s.fin', [$now->format('H:i:s')])
            ->first();
    }
}

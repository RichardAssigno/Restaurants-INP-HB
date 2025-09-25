<?php

namespace App\Http\Controllers;

use App\Models\Facturation;
use App\Models\Operateur;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TableaudebordController extends Controller
{

    public function index(){

        $operateur = Operateur::getInfoOperateur(Auth::guard("operateur")->id());

        $infosfacturationdujour = Transaction::getFacturationsParServiceDuJour($operateur->idPrestataire, Carbon::today()->toDateString());

        $petitdejeuner = $infosfacturationdujour->firstWhere('codeService', 'PD');
        $dejeuner = $infosfacturationdujour->firstWhere('codeService', 'D');
        $diner = $infosfacturationdujour->firstWhere('codeService', 'DR');
        $datepouraffichage = Carbon::today()->format('d.m.Y');

        $mois = carbon::today()->format('m');
        $annee = carbon::today()->format('Y');

        $facturationparmois = Transaction::statistiquesParJour($mois, $annee, $operateur->idPrestataire);

        $moisFrancais = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

       /* $moisOptions = [];
        $aujourdHui = Carbon::now();

        for ($i = 0; $i < 12; $i++) {
            $date = $aujourdHui->copy()->subMonths($i);
            $moisOptions[] = [
                'value' => $date->format('n'), // ex: 9-2025
                'label' => $moisFrancais[(int)$date->format('n')] . ' ' . $date->format('Y')
            ];
        }*/

        return view('tableaudebord.index', [

            "title" => "Tableau de bord",
            "petitdejeuner" => $petitdejeuner,
            "dejeuner" => $dejeuner,
            "diner" => $diner,
            "date" => $datepouraffichage,
            "facturationparmois" => $facturationparmois,
            "moisFrancais" => $moisFrancais,

        ]);

    }

    public function recuperer(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mois' => ['required', 'string'],
            'annee' => ['required', 'string'],
        ], [
            "mois.required" => "Le mois est obligatoire",
            "mois.string" => "Le mois doit être de type chaîne de caractère",
            "annee.required" => "L'année est obligatoire",
            "annee.string" => "L'année doit être de type chaîne de caractère",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        $operateur = Operateur::getInfoOperateur(Auth::guard("operateur")->id());

        $transactionsparmois = Transaction::statistiquesParJour($data["mois"], $data["annee"], $operateur->idPrestataire);

        if ($transactionsparmois->isNotEmpty()) {

            return response()->json([
                'success' => true,
                'data' => $transactionsparmois
            ]);

        }

        return response()->json([
            'errors' => [
                'general' => ['Aucune donnée trouvée']
            ]
        ], 422);



    }

    public function pdf(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mois' => ['required', 'string'],
            'annee' => ['required', 'string'],
        ], [
            "mois.required" => "Le mois est obligatoire",
            "mois.string" => "Le mois doit être de type chaîne de caractère",
            "annee.required" => "L'année est obligatoire",
            "annee.string" => "L'année doit être de type chaîne de caractère",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        $operateur = Operateur::getInfoOperateur(Auth::guard("operateur")->id());

        $facturationparmois = Transaction::statistiquesParJour($data["mois"], $data["annee"], $operateur->idPrestataire);

        $totalservicesparMois = Transaction::totalServicesParMois($data["mois"], $operateur->idPrestataire);

        $moisselectionne = Carbon::create()->month((int)$data["mois"])->locale('fr')->monthName;

        $anneeselectionne = $data["annee"];

        if ($facturationparmois->isNotEmpty()){

            return view('bilanPrestataires.index', [

                "title" => "Bilan du restaurant",
                "facturationparmois" => $facturationparmois,
                "totalservicesparMois" => $totalservicesparMois,
                "moisselectionne" => $moisselectionne,
                "anneeselectionne" => $anneeselectionne,

            ]);

        }

        return redirect()->back()->with('error', 'Aucune transaction effectuer pour le mois sélectionné.');


    }

}

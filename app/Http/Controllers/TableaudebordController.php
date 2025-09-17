<?php

namespace App\Http\Controllers;

use App\Models\Facturation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TableaudebordController extends Controller
{

    public function index($mois = null){

        $infosfacturationdujour = Facturation::getFacturationsParServiceDuJour(Carbon::today()->toDateString());

        $petitdejeuner = $infosfacturationdujour->firstWhere('codeService', 'PD');
        $dejeuner = $infosfacturationdujour->firstWhere('codeService', 'D');
        $diner = $infosfacturationdujour->firstWhere('codeService', 'DR');
        $datepouraffichage = Carbon::today()->format('d.m.Y');

        $mois = $mois ?? carbon::today()->format('m');

        $facturationparmois = Facturation::statistiquesParJour($mois);

        $moisFrancais = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        $moisOptions = [];
        $aujourdHui = Carbon::now();

        for ($i = 0; $i < 12; $i++) {
            $date = $aujourdHui->copy()->subMonths($i);
            $moisOptions[] = [
                'value' => $date->format('n'), // ex: 9-2025
                'label' => $moisFrancais[(int)$date->format('n')] . ' ' . $date->format('Y')
            ];
        }

        return view('tableaudebord.index', [

            "title" => "Tableau de bord",
            "petitdejeuner" => $petitdejeuner,
            "dejeuner" => $dejeuner,
            "diner" => $diner,
            "date" => $datepouraffichage,
            "facturationparmois" => $facturationparmois,
            "moisOptions" => $moisOptions,

        ]);

    }

    public function recuperer(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mois' => ['required', 'string'],
        ], [
            "mois.required" => "Le mois est obligatoire",
            "mois.string" => "Le mois doit être de type chaîne de caractère",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        $facturationparmois = Facturation::statistiquesParJour($data["mois"]);

        return response()->json([
            'success' => true,
            'data' => $facturationparmois
        ]);

    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\CompteRestau;
use App\Models\TypeFacturation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComptesRestauxController extends Controller
{

    public function index()
    {

        return view('compterestaux.index', [

            "title" => "Comptes Restaurant",
            "typesfacturations" => TypeFacturation::query()->where("supprimer",0)->orderBy("libelle", "asc")->get(),
            "comptesrestaux" => Compte::getComptesRestaux(),

        ]);

    }

    public function ajouter(Request $request){


        $validator = Validator::make($request->all(), [
            'matricule' => ['required', 'string'],
            'typefacturation' => ['required'],
        ], [
            "matricule.required" => "Le matricule est obligatoire",
            "matricule.string" => "Le matricule doit être de type chaîne de caractère",
            "typefacturation.required" => "Le type de facturation est obligatoire",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        $compte = CompteRestau::getFacturationsByMatricule($data['matricule']);

        if($compte->isNotEmpty()){



        }


            dd($data);

    }


}

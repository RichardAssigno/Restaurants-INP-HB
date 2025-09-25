<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Transaction;
use Illuminate\Http\Request;

class RechercherController extends Controller
{

    public function rechercher(Request $request)
    {
        $query = $request->query('query');

        if (!$query) {
            return response()->json([], 200);
        }

        $results = Etudiant::searchstudents($query);

        return response()->json($results, 200);
    }

    public function afficher($id)
    {

        $etudiant = Etudiant::getEtudiantAvecPhoto($id);
        $infostransactions = Transaction::dernieresTransactions($etudiant->idCompte);

        return view('bilansetudiants.index', [

            'infostransactions' => $infostransactions,
            'etudiant' => $etudiant,

        ]);

    }


}

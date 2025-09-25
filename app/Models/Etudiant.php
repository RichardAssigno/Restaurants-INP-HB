<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Etudiant extends Model
{

    protected $table = 'etudiants';


    public static function searchstudents($tag){

        return static :: query()->select('et.*')
            ->from('etudiants as et')
            ->where('searchTag', 'LIKE', '%'.$tag.'%')
            ->orderBy('nom')
            ->orderBy('prenoms')
            ->get();

    }

    public static function getEtudiantAvecPhoto($idEtudiant)
    {
        return DB::table('etudiants as e')
            ->leftJoin('photos as p', 'p.etudiants_id', '=', 'e.id')
            ->join('comptesrestaux as cr', 'cr.etudiants_id', '=', 'e.id')
            ->join('typescomptes as tc', function($join) {
                $join->on('tc.id', '=', 'cr.typescomptes_id')
                    ->where('tc.supprimer', 0);
            })
            ->join('facturations as f', function($join) {
                $join->on('f.compterestaux_id', '=', 'cr.id')
                    ->where('f.supprimer', 0);
            })
            ->join('typesfacturations as tf', function($join) {
                $join->on('tf.id', '=', 'f.typesFacturations_id')
                    ->where('tf.supprimer', 0);
            })
            ->select(
                'e.*',
                DB::raw('TO_BASE64(p.photo) as photo'),
                'p.typePhoto',
                'cr.id as idCompte',
                'cr.capacite',
                'cr.solde',
                'cr.traques',
                'cr.actif',
                'tc.libelle as libelleTypeCompte',
                'tc.codeTypeCompte',
                'tf.libelle as libelleTypeFacturation',
                'tf.codeTypeFacturations',
                'tf.modeRechargement'
            )
            ->where('e.id', $idEtudiant)
            ->groupBy('e.id')
            ->first();

    }

}

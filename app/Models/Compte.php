<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Compte extends Model
{

    protected $table = 'comptesrestaux';

    protected $fillable = [

        'etudiants_id',
        'typescomptes_id',
        'pin',
        'solde',
        'actif',
        'traques',
        'userAdd',
        'userUpdate',
        'userDelete',
        'deleted_at',
        'supprimer',

    ];

    public static function getCompteActifParHeure($pin, $heure)
    {
        return DB::table('comptesrestaux as cr')
            ->leftJoin('carteslibres as cl', 'cl.id', '=', 'cr.carteslibres_id')
            ->leftJoin('directions as d', 'd.id', '=', 'cl.directions_id')
            ->join('typescomptes as tc', 'tc.id', '=', 'cr.typescomptes_id')
            ->join('facturations as f', 'f.compterestaux_id', '=', 'cr.id')
            ->join('typesfacturations as tf', 'tf.id', '=', 'f.typesFacturations_id')
            ->join('prix as p', 'p.id', '=', 'f.prix_id')
            ->join('services as s', 's.id', '=', 'p.services_id')
            ->where('cr.pin', '=', $pin)
            ->whereRaw('? BETWEEN s.debut AND s.fin', [$heure])
            ->select(
                'cr.id as idCompte',
                'cr.pin',
                'cr.solde',
                'cr.actif',
                'cr.traques',
                'cl.id as idCarte',
                'cl.libelle as libelleCarte',
                'cl.capacite',
                'd.libelle as libelleDirection',
                'tc.libelle as libelleTypeCompte',
                'f.id as idFacturation',
                'tf.libelle as libelleTypeFacturation',
                'tf.codeTypeFacturations',
                'tf.modeRechargement',
                'p.id as idPrix',
                'p.valeur',
                's.id as idService',
                's.codeService',
                's.libelle as libelleService',
                's.debut',
                's.fin',
                's.weekendDebut',
                's.weekendFin',
                's.congesDebut',
                's.congesFin'
            )
            ->first(); // ou ->get() si tu veux plusieurs résultats
    }


    public static function getInfosTransactionsTousOperateurs($heure)
    {
        return DB::table('operateurs as o')
            ->select(
                'o.id as idOperateur',
                'o.nom as nomOperateur',
                DB::raw('COALESCE(COUNT(t.id), 0) as totalTransaction'),
                'p.valeur',
                's.id as idService',
                's.codeService',
                's.libelle as libelleService'
            )
            ->join('prix as p', function ($join) {
                $join->where('p.supprimer', '=', 0);
            })
            ->join('services as s', function ($join) {
                $join->on('s.id', '=', 'p.services_id')
                    ->where('s.supprimer', '=', 0);
            })
            ->leftJoin('transactions as t', function ($join) use ($heure) {
                $join->on('t.prix_id', '=', 'p.id')
                    ->whereDate('t.created_at', now());
            })
            ->whereRaw('? BETWEEN s.debut AND s.fin', [$heure])
            ->groupBy('o.id', 'p.valeur', 's.id', 's.codeService', 's.libelle')
            ->first();
    }


    public static function getEtudiantsOperateurDuJour($idOperateur, $heure)
    {
        return DB::table('operateurs as o')
            ->join('transactions as t', 't.operateurs_id', '=', 'o.id')
            ->join('comptesrestaux as cr', 'cr.id', '=', 't.comptesrestaux_id')
            ->join('etudiants as e', 'e.id', '=', 'cr.etudiants_id')
            ->leftJoin('photos as ph', 'ph.etudiants_id', '=', 'e.id')
            ->join('prix as p', function ($join) {
                $join->on('p.id', '=', 't.prix_id')
                    ->where('p.supprimer', '=', 0);
            })
            ->join('services as s', function ($join) {
                $join->on('s.id', '=', 'p.services_id')
                    ->where('s.supprimer', '=', 0);
            })
            ->where('o.id', '=', $idOperateur)
            ->whereRaw('? BETWEEN s.debut AND s.fin', [$heure])
            ->whereDate('t.created_at', now()) // équivalent DATE(t.created_at) = CURDATE()
            ->select(
                'e.nom',
                'e.matricule',
                'e.prenoms',
                'e.telephone',
                'o.nom as nomOperateur',
                't.created_at as dateTransaction',
                DB::raw('TO_BASE64(ph.photo) as photo'),
                'ph.typePhoto',
                DB::raw('COUNT(t.id) as totalTransactions') // 👈 nombre de transactions par étudiant
            )
            ->orderBy('t.created_at', 'desc')
            ->groupBy('e.nom', 'e.matricule', 'e.prenoms', 'e.telephone', 'o.nom')
            ->get();
    }

    public static function getComptesRestaux()
    {
        return DB::table('comptesrestaux as cr')
            ->join('etudiants as e', 'e.id', '=', 'cr.etudiants_id')
            ->join('typescomptes as tc', function ($join) {
                $join->on('tc.id', '=', 'cr.typescomptes_id')
                    ->where('tc.supprimer', '=', 0);
            })
            ->join('facturations as f', function ($join) {
                $join->on('f.compterestaux_id', '=', 'cr.id')
                    ->where('f.supprimer', '=', 0);
            })
            ->join('typesfacturations as tf', function ($join) {
                $join->on('tf.id', '=', 'f.typesFacturations_id')
                    ->where('tf.supprimer', '=', 0);
            })
            ->leftJoin('carteslibres as cl', 'cl.id', '=', 'cr.carteslibres_id')
            ->leftJoin('directions as d', 'd.id', '=', 'cl.directions_id')
            ->where('cr.supprimer', '=', 0)
            ->select(
                'cr.id as idCompteRestau',
                'cr.pin',
                'cr.capacite',
                'cr.solde',
                'cr.actif',
                'cr.traques',
                'e.id as idEtudiant',
                'e.matricule',
                'e.nom',
                'e.prenoms',
                'tc.libelle as libelleTypeCompte',
                'tf.libelle as libelleTypeFacturation',
                'cl.id as idCarteLibre',
                'cl.libelle as libelleCarteLibre',
                'd.libelle as libelleDirection'
            )
            ->groupBy('cr.id')
            ->get();
    }






}

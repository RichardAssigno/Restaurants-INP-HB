<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{

    protected $table = 'transactions';

    protected $fillable = [
        'comptesrestaux_id',
        'prix_id',
        'operateurs_id',
        'trimestresAnnees_id',
        'userAdd',
        'userUpdate',
        'userDelete',
        'deleted_at',
        'supprimer',
    ];

    public static function getFacturationsParServiceDuJour($prestataireId, $date)
    {
        return DB::table('services as s')
            ->join('prix as p', 'p.services_id', '=', 's.id')
            ->leftJoin('transactions as t', function($join) use ($date, $prestataireId) {
                $join->on('t.prix_id', '=', 'p.id')
                    ->whereDate('t.created_at', $date)
                    ->whereIn('t.operateurs_id', function($query) use ($prestataireId) {
                        $query->select('o.id')
                            ->from('operateurs as o')
                            ->join('operateursprestataires as op', function($q) {
                                $q->on('op.operateurs_id', '=', 'o.id')
                                    ->where('op.supprimer', 0);
                            })
                            ->where('op.prestataires_id', $prestataireId)
                            ->where('o.supprimer', 0);
                    });
            })
            ->select(
                's.id as idService',
                's.libelle',
                's.codeService',
                DB::raw('COALESCE(COUNT(t.id), 0) as totalFacturations'),
                'p.valeur'
            )
            ->groupBy('s.id', 's.libelle', 's.codeService', 'p.valeur')
            ->get();
    }


    public static function statistiquesParJour($mois, $annee, $idPrestataire)
    {
        return DB::table('transactions as t')
            ->join('operateurs as o', function($join) {
                $join->on('o.id', '=', 't.operateurs_id')
                    ->where('o.supprimer', 0);
            })
            ->join('operateursprestataires as op', function($join) {
                $join->on('op.operateurs_id', '=', 'o.id')
                    ->where('op.supprimer', 0);
            })
            ->join('prestataires as pr', function($join) {
                $join->on('pr.id', '=', 'op.prestataires_id')
                    ->where('pr.supprimer', 0);
            })
            ->join('prix as p', 't.prix_id', '=', 'p.id')
            ->join('services as s', 'p.services_id', '=', 's.id')
            ->select(
                DB::raw('DATE(t.created_at) as jour'),
                DB::raw("SUM(CASE WHEN s.codeService = 'PD' THEN 1 ELSE 0 END) as petit_dejeuner"),
                DB::raw("SUM(CASE WHEN s.codeService = 'D' THEN 1 ELSE 0 END) as dejeuner"),
                DB::raw("SUM(CASE WHEN s.codeService = 'DR' THEN 1 ELSE 0 END) as diner"),
                DB::raw('COUNT(t.id) as total'),
                'o.nom',
                'o.prenoms',
                'pr.libelle as libellePrestataire',
                'pr.codePrestataire'
            )
            ->whereMonth('t.created_at', $mois)
            ->whereYear('t.created_at', $annee) // ✅ ajout de l'année
            ->where('pr.id', $idPrestataire)
            ->groupBy(DB::raw('DATE(t.created_at)'))
            ->orderBy(DB::raw('DATE(t.created_at)'), 'ASC')
            ->get();
    }



    /*public static function statistiquesParJour($mois)
    {
        return DB::table('transactions as t')
            ->join('prix as p', 't.prix_id', '=', 'p.id')
            ->join('services as s', 'p.services_id', '=', 's.id')
            ->select(
                DB::raw('DATE(t.created_at) as jour'),
                DB::raw("SUM(CASE WHEN s.codeService = 'PD' THEN 1 ELSE 0 END) as petit_dejeuner"),
                DB::raw("SUM(CASE WHEN s.codeService = 'D' THEN 1 ELSE 0 END) as dejeuner"),
                DB::raw("SUM(CASE WHEN s.codeService = 'DR' THEN 1 ELSE 0 END) as diner"),
                DB::raw('COUNT(t.id) as total')
            )
            ->whereMonth('t.created_at', $mois)
            ->groupBy(DB::raw('DATE(t.created_at)'))
            ->orderBy(DB::raw('DATE(t.created_at)'), 'ASC')
            ->get();
    }*/

    public static function transactionaujourdhui($compteId, $codeService)
    {
        return static::query()
            ->select(
                't.*',
                'p.valeur as prix_valeur',
                's.libelle as service_libelle',
                's.codeService as service_code'
            )
            ->from('transactions as t')
            ->join('prix as p', 'p.id', '=', 't.prix_id')
            ->join('services as s', 's.id', '=', 'p.services_id')
            ->where('t.comptesrestaux_id', '=', $compteId)
            ->where('s.codeService', '=', $codeService)
            ->whereDate('t.created_at', now()) // équivalent de DATE(t.created_at) = CURDATE()
            ->first();
    }

    public static function transactioncartelibre($compteId, $codeService)
    {
        return static::query()
            ->select(
                't.*',
                'p.valeur as prix_valeur',
                's.libelle as service_libelle',
                's.codeService as service_code'
            )
            ->from('transactions as t')
            ->join('comptesrestaux as cr', 'cr.id', '=', 't.comptesrestaux_id')
            ->join('carteslibres as cl', 'cl.id', '=', 'cr.carteslibres_id')
            ->join('directions as d', 'd.id', '=', 'cl.directions_id')
            ->join('prix as p', 'p.id', '=', 't.prix_id')
            ->join('services as s', 's.id', '=', 'p.services_id')
            ->where('t.comptesrestaux_id', '=', $compteId)
            ->where('s.codeService', '=', $codeService)
            ->whereDate('t.created_at', now()) // équivalent de DATE(t.created_at) = CURDATE()
            ->get();
    }

    public static function totalServicesParMois($mois, $idPrestataire)
    {
        return DB::table('transactions as t')
            ->join('operateurs as o', function($join) {
                $join->on('o.id', '=', 't.operateurs_id')
                    ->where('o.supprimer', 0);
            })
            ->join('operateursprestataires as op', function($join) {
                $join->on('op.operateurs_id', '=', 'o.id')
                    ->where('op.supprimer', 0);
            })
            ->join('prestataires as pr', function($join) use ($idPrestataire) {
                $join->on('pr.id', '=', 'op.prestataires_id')
                    ->where('pr.supprimer', 0)
                    ->where('pr.id', $idPrestataire);
            })
            ->join('prix as p', 't.prix_id', '=', 'p.id')
            ->join('services as s', 'p.services_id', '=', 's.id')
            ->select(
                DB::raw("SUM(CASE WHEN s.codeService = 'PD' THEN 1 ELSE 0 END) AS petit_dejeuner"),
                DB::raw("SUM(CASE WHEN s.codeService = 'D' THEN 1 ELSE 0 END) AS dejeuner"),
                DB::raw("SUM(CASE WHEN s.codeService = 'DR' THEN 1 ELSE 0 END) AS diner"),
                DB::raw("COUNT(t.id) AS total")
            )
            ->whereMonth('t.created_at', $mois)
            ->first(); // un seul résultat pour le mois
    }

    public static function getTransactions30DerniersJours($etudiantId)
    {
        $dateLimite = Carbon::now()->subDays(30)->startOfDay();

        $transactions = DB::table('etudiants as e')
            ->join('comptesrestaux as cr', function($join) {
                $join->on('cr.etudiants_id', '=', 'e.id')
                    ->where('cr.supprimer', 0);
            })
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
            ->join('transactions as t', 't.comptesrestaux_id', '=', 'cr.id')
            ->join('prix as p', function($join) {
                $join->on('p.id', '=', 't.prix_id')
                    ->where('p.supprimer', 0);
            })
            ->join('services as s', function($join) {
                $join->on('s.id', '=', 'p.services_id')
                    ->where('s.supprimer', 0);
            })
            ->join('operateurs as o', function($join) {
                $join->on('o.id', '=', 't.operateurs_id')
                    ->where('o.supprimer', 0);
            })
            ->join('operateursprestataires as op', function($join) {
                $join->on('op.operateurs_id', '=', 'o.id')
                    ->where('op.supprimer', 0);
            })
            ->join('prestataires as pr', function($join) {
                $join->on('pr.id', '=', 'op.prestataires_id')
                    ->where('pr.supprimer', 0);
            })
            ->where('e.id', $etudiantId)
            ->where('t.created_at', '>=', $dateLimite)
            ->select(
                'e.id as idEtudiant',
                'e.matricule',
                'e.nom',
                'e.prenoms',
                'e.telephone',
                'cr.id as idCompteRestau',
                'cr.solde',
                'cr.actif',
                'cr.traques',
                'tc.libelle as libelleTypeCompte',
                'tc.codeTypeCompte',
                'tf.libelle as libelleTypeFacturation',
                'tf.codeTypeFacturations',
                'tf.modeRechargement',
                'p.valeur',
                's.libelle as libelleService',
                's.codeService',
                't.id as idTransaction',
                't.created_at',
                'o.nom as nomOperateur',
                'o.prenoms as prenomsOperateur',
                'o.contact',
                'o.actif as operateurActif',
                'pr.localisation',
                'pr.libelle as libellePrestataire',
                'pr.codePrestataire',
            )
            ->groupBy('t.id')
            ->orderByDesc('t.created_at')
            ->get();

        return $transactions;
    }

    public static function dernieresTransactions($idCompteRestau)
    {
        return DB::table('transactions as t')
            ->join('operateurs as o', function($join) {
                $join->on('o.id', '=', 't.operateurs_id')
                    ->where('o.supprimer', 0);
            })
            ->join('operateursprestataires as op', function($join) {
                $join->on('op.operateurs_id', '=', 'o.id')
                    ->where('op.supprimer', 0);
            })
            ->join('prestataires as pr', function($join) {
                $join->on('pr.id', '=', 'op.prestataires_id')
                    ->where('pr.supprimer', 0);
            })
            ->join('prix as p', 't.prix_id', '=', 'p.id')
            ->join('services as s', 'p.services_id', '=', 's.id')
            ->select(
                't.id as idTransaction',
                't.created_at',
                'p.valeur',
                's.libelle as libelleService',
                's.codeService',
                'o.nom as nomOperateur',
                'o.prenoms as prenomsOperateur',
                'o.contact as contactOperateur',
                'pr.libelle as libellePrestataire',
                'pr.localisation as localisationPrestataire',
                'pr.codePrestataire'
            )
            ->where('t.comptesrestaux_id', $idCompteRestau)
            ->orderByDesc('t.created_at')
            ->limit(30)
            ->get();
    }




}

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

    public static function getCompteActifParHeure($pin, $heure, bool $forUpdate = false)
    {
        $query = self::requeteCompteFacturable($pin)
            ->whereRaw('? BETWEEN s.debut AND s.fin', [$heure]);

        if ($forUpdate) {
            $query->lockForUpdate();
        }

        return $query->first();
    }

    /* public static function getCompteActifParService($pin, $serviceId)
     {
         $query = self::requeteCompteFacturable($pin)
             ->where('s.id', '=', $serviceId);

         return $query->first();
     }*/

    public static function getCompteActifParService($pin, $serviceId)
    {
        return DB::table('comptesrestaux as cr')

            ->leftJoin('etudiants as e', 'e.id', '=', 'cr.etudiants_id')

            ->leftJoin('carteslibres as cl', 'cl.id', '=', 'cr.carteslibres_id')

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

            ->join('prix as p', function ($join) {
                $join->on('p.id', '=', 'f.prix_id')
                    ->where('p.supprimer', '=', 0);
            })

            ->join('services as s', function ($join) {
                $join->on('s.id', '=', 'p.services_id')
                    ->where('s.supprimer', '=', 0);
            })

            ->where('cr.supprimer', '=', 0)
            ->where('cr.pin', '=', trim($pin))
            ->where('s.id', '=', $serviceId)

            ->select(
                'e.id as idEtudiant',
                'e.nom',
                'e.prenoms',
                'e.matricule',

                'cr.id as idCompte',
                'cr.pin',
                'cr.solde',
                'cr.actif',
                'cr.traques',

                'cl.id as idCarte',
                'cl.libelle as libelleCarte',
                'cl.capacite',
                'cl.dateDebut',
                'cl.nombreJours',
                'cl.actif as actifCarteLibre',
                'cl.supprimer as carteLibreSupprimee',

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

            ->first();
    }

    public static function getInfosTransactionsTousOperateurs($operateurId, $serviceId)
    {
        return DB::table('transactions as t')
            ->join('operateurs as o', 'o.id', '=', 't.operateurs_id')

            ->join('operateursprestataires as op', function ($join) {
                $join->on('op.operateurs_id', '=', 'o.id')
                    ->where('op.supprimer', 0)
                    ->where('op.statut', 0)
                    ->whereNull('op.dateFin');
            })

            ->join('prestataires as p', 'p.id', '=', 'op.prestataires_id')

            ->join('prix as pr', 'pr.id', '=', 't.prix_id')

            ->join('services as s', 's.id', '=', 'pr.services_id')

            ->join('comptesrestaux as cr', 'cr.id', '=', 't.comptesrestaux_id')

            ->leftjoin('etudiants as e', 'e.id', '=', 'cr.etudiants_id')

            ->where('s.id', '=', $serviceId)
            ->where('o.id', '=', $operateurId)
            ->whereDate('t.created_at', '=', date('Y-m-d'))

            ->select(
                'o.id as idOperateur',
                'o.nom as nomOperateur',
                DB::raw('COALESCE(COUNT(t.id), 0) as totalTransaction'),
                's.id as idService',
                's.codeService',
                's.libelle as libelleService',
                'pr.valeur'
            )

            ->groupBy(
                'o.id',
                'o.nom',
                's.id',
                's.codeService',
                's.libelle'
            )

            ->first();
    }

    public static function getEtudiantsOperateurDuJour($idOperateur, $serviceId = null)
    {
        $dateJour = date('Y-m-d');
        $query = DB::table('operateurs as o')
            ->join('transactions as t', 't.operateurs_id', '=', 'o.id')
            ->join('comptesrestaux as cr', 'cr.id', '=', 't.comptesrestaux_id')
            ->leftjoin('carteslibres as cl', 'cl.id', '=', 'cr.carteslibres_id')
            ->leftjoin('directions as dr', 'dr.id', '=', 'cl.directions_id')
            ->leftjoin('etudiants as e', 'e.id', '=', 'cr.etudiants_id')
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
            ->when($serviceId, function ($query) use ($serviceId) {
                $query->where('s.id', '=', $serviceId);
            }, function ($query) use ($dateJour) {
                $query->whereRaw('? BETWEEN s.debut AND s.fin', [$dateJour]);
            })
            ->whereDate('t.created_at', now()) // équivalent DATE(t.created_at) = CURDATE()
            ->select(
                'cr.id as idCompteRestau',
                'e.nom',
                'e.id as idEtudiant',
                'e.matricule',
                'e.prenoms',
                'e.telephone',
                'o.nom as nomOperateur',
                DB::raw('MAX(t.created_at) as dateTransaction'),
                DB::raw('MAX(TO_BASE64(ph.photo)) as photo'),
                DB::raw('MAX(ph.typePhoto) as typePhoto'),
                DB::raw('COUNT(t.id) as totalTransactions'),
                DB::raw('MAX(cr.capacite) as capacite'),
                DB::raw('MAX(cl.libelle) as libelleCarteLibre'),
                DB::raw('MAX(dr.libelle) as libelleDirection')
            )
            ->groupBy('cr.id', 'e.id', 'e.nom', 'e.matricule', 'e.prenoms', 'e.telephone', 'o.nom')
            ->orderByDesc('dateTransaction');

        return $query->get();
    }

    public static function getCompteLibreAvecDetails($idCompte)
    {
        return DB::table('comptesrestaux as cr')
            ->join('carteslibres as cl', function ($join) {
                $join->on('cl.id', '=', 'cr.carteslibres_id')
                    ->where('cl.supprimer', '=', 0);
            })
            ->leftJoin('directions as d', function ($join) {
                $join->on('d.id', '=', 'cl.directions_id')
                    ->where('d.supprimer', '=', 0);
            })
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
            ->where('cr.id', '=', $idCompte)
            ->where('cr.supprimer', '=', 0)
            ->whereNull('cr.etudiants_id')
            ->whereNotNull('cr.carteslibres_id')
            ->select(
                'cr.id as idCompte',
                'cr.capacite',
                'cr.solde',
                'cr.actif',
                'cr.traques',
                'cl.id as idCarteLibre',
                'cl.libelle as libelleCarteLibre',
                'cl.dateDebut',
                'cl.nombreJours',
                'd.libelle as libelleDirection',
                'd.codeDirection',
                'tc.libelle as libelleTypeCompte',
                'tf.libelle as libelleTypeFacturation',
                'tf.codeTypeFacturations',
                'tf.modeRechargement'
            )
            ->orderByDesc('f.id')
            ->first();
    }

    public static function getComptesRestaux()
    {
        $facturationParCompte = DB::table('facturations as f')
            ->where('f.supprimer', '=', 0)
            ->select(
                'f.compterestaux_id',
                DB::raw('MIN(f.typesFacturations_id) as typesFacturations_id')
            )
            ->groupBy('f.compterestaux_id');

        return DB::table('comptesrestaux as cr')
            ->join('etudiants as e', 'e.id', '=', 'cr.etudiants_id')
            ->join('typescomptes as tc', function ($join) {
                $join->on('tc.id', '=', 'cr.typescomptes_id')
                    ->where('tc.supprimer', '=', 0);
            })
            ->joinSub($facturationParCompte, 'f', function ($join) {
                $join->on('f.compterestaux_id', '=', 'cr.id');
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
                'tf.id as idTypeFacturation',
                'tf.libelle as libelleTypeFacturation',
                'tf.modeRechargement',
                'cl.id as idCarteLibre',
                'cl.libelle as libelleCarteLibre',
                'd.libelle as libelleDirection'
            )
            ->get();
    }
}

<?php

namespace App\Models;

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


    public static function getFacturationsParServiceDuJour($date)
    {
        return DB::table('services as s')
            ->join('prix as p', 'p.services_id', '=', 's.id')
            ->leftJoin('transactions as t', function ($join) use ($date) {
                $join->on('t.prix_id', '=', 'p.id')
                    ->whereDate('t.created_at', '=', $date);
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

    public static function statistiquesParJour($mois)
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
    }

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



}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Facturation extends Model
{

    protected $table = 'facturations';

    protected $fillable = [



    ];

    public static function getFacturationsParServiceDuJour($date)
    {
        return DB::table('services as s')
            ->join('prix as p', 'p.services_id', '=', 's.id')
            ->leftJoin('facturations as f', function ($join) use ($date) {
                $join->on('f.prix_id', '=', 'p.id')
                    ->whereDate('f.created_at', '=', $date);
            })
            ->select(
                's.id as idService',
                's.libelle',
                's.codeService',
                DB::raw('COALESCE(COUNT(f.id), 0) as totalFacturations'),
                'p.valeur'
            )
            ->groupBy('s.id', 's.libelle', 's.codeService', 'p.valeur')
            ->get();
    }

    public static function statistiquesParJour($mois)
    {
        return DB::table('facturations as f')
            ->join('prix as p', 'f.prix_id', '=', 'p.id')
            ->join('services as s', 'p.services_id', '=', 's.id')
            ->select(
                DB::raw('DATE(f.created_at) as jour'),
                DB::raw("SUM(CASE WHEN s.codeService = 'PD' THEN 1 ELSE 0 END) as petit_dejeuner"),
                DB::raw("SUM(CASE WHEN s.codeService = 'D' THEN 1 ELSE 0 END) as dejeuner"),
                DB::raw("SUM(CASE WHEN s.codeService = 'DR' THEN 1 ELSE 0 END) as diner"),
                DB::raw('COUNT(f.id) as total')
            )
            ->whereMonth('f.created_at', $mois)
            ->groupBy(DB::raw('DATE(f.created_at)'))
            ->orderBy(DB::raw('DATE(f.created_at)'), 'ASC')
            ->get();
    }


}

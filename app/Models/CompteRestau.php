<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompteRestau extends Model
{

    protected $table = "comptesrestaux";

    protected $fillable = [

        "etudiants_id",
        "typescomptes_id",
        "carteslibres_id",
        "pin",
        "capacite",
        "solde",
        "actif",
        "traques",
        "userAdd",
        "userUpdate",
        "userDelete",
        "deleted_at",
        "supprimer",

    ];

    public static function getFacturationsByMatricule($matricule)
    {
        return DB::table('comptesrestaux as cr')
            ->join('etudiants as e', 'e.id', '=', 'cr.etudiants_id')
            ->leftJoin('facturations as f', function ($join) {
                $join->on('f.compterestaux_id', '=', 'cr.id')
                    ->where('f.supprimer', '=', 0);
            })
            ->where('e.matricule', $matricule)
            ->select('cr.*', 'e.*', 'f.*')
            ->get();
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorairesSepciaux extends Model
{
    use HasFactory;

    protected $table = 'horairesspeciaux';

    protected $fillable = [

        "motif",
        "services_id",
        "prestataires_id",
        "dateHoraire",
        "heureDebut",
        "heureFin",
        "userAdd",
        "userUpdate",
        "userDelete",
        "deleted_at",
        "supprimer",

    ];

    public static function listeHorairesSpeciaux()
    {
        return self::select(
            'horairesspeciaux.*',
            's.libelle as libelleService',
            'p.libelle as libellePrestataire'
        )
            ->join('services as s', function($join) {
                $join->on('s.id', '=', 'horairesspeciaux.services_id')
                    ->where('s.supprimer', 0);
            })
            ->join('prestataires as p', function($join) {
                $join->on('p.id', '=', 'horairesspeciaux.prestataires_id')
                    ->where('p.supprimer', 0);
            })
            ->get();
    }

}

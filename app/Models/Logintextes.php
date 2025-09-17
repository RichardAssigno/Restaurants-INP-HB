<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Logintextes extends Model
{
    use HasFactory;

    protected $table = "logintextes";

    protected $fillable = [
        'texte', 'created_at', 'userAdd',
        'updated_at', 'userUpdate', 'statut',
        'userDelete', 'deleted_at', 'supprimer'
    ];

    public static function listeLogintextes()
    {

        return static:: query()->select('lt.texte', 'lt.id', 'lt.statut', 'lt.created_at')
            ->from('logintextes as lt')
            ->where('lt.supprimer', '=', '0')
            ->orderBy('lt.id', 'desc')
            ->get();
    }

    public static function getLogintexte($id)
    {

        return static::query()->select('lt.*')
            ->from('logintextes as lt')
            ->where('lt.id', '=', $id)
            ->where('lt.supprimer', '=', 0)
            ->first();

    }




    public static function getTexte()
    {
        $resultat = DB::query()
            ->select('lt.*')
            ->from('logintextes as lt')
            ->where('lt.supprimer', '=', 0)
            ->orderByRaw('lt.statut = 1 DESC') // Priorité à statut = 1
            ->orderBy('lt.id') // Ensuite, prendre celui avec id = 1 si aucun statut = 1
            ->first();

        return $resultat;
    }


}

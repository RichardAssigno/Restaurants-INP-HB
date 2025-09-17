<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Loginimages extends Model
{
    use HasFactory;

    protected $table = "loginimages";

    protected $fillable = [
        'image', 'typeimage', 'urlImage', 'created_at', 'userAdd',
        'updated_at', 'userUpdate', 'statut',
        'userDelete', 'deleted_at', 'supprimer'
    ];

    public static function listeLoginimages()
    {

        return static:: query()->select('li.id', 'li.typeimage', 'li.statut',
            DB::raw("CASE
                WHEN  li.image IS NOT NULL AND li.image != ''
                     THEN CONCAT('data:', li.typeimage, ';base64,', TO_BASE64(li.image))
                ELSE NULL
             END AS loginImage"),
            'li.created_at')
            ->from('loginimages as li')
            ->where('li.supprimer', '=', '0')
            ->orderBy('li.id', 'desc')
            ->get();
    }

    public static function getLoginimage($id)
    {

        return static:: query()->select('li.id', 'li.typeimage', 'li.statut',
            DB::raw("CASE
                WHEN  li.image IS NOT NULL AND li.image != ''
                     THEN CONCAT('data:', li.typeimage, ';base64,', TO_BASE64(li.image))
                ELSE NULL
             END AS loginImage"),
            'li.created_at')
            ->from('loginimages as li')
            ->where('li.id', '=', $id)
            ->where('li.supprimer', '=', '0')
            ->first();
    }

    public static function getImage()
    {

        $resultat = DB:: query()->select('li.id', 'li.urlImage', 'li.statut',
            /*DB::raw("CASE
                WHEN  li.image IS NOT NULL AND li.image != ''
                     THEN CONCAT('data:', li.typeimage, ';base64,', TO_BASE64(li.image))
                ELSE NULL
             END AS loginImage"),
            */
            'li.created_at')
            ->from('loginimages as li')
            ->where('li.supprimer', '=', 0)
            ->orderByRaw('li.statut = 1 DESC') // Priorité à statut = 1
            ->orderBy('li.id')
            ->first();
        return $resultat;
    }

}

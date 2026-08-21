<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Prestataire extends Model
{
    protected $table = 'prestataires';

    protected $fillable = [

        'libelle',
        'codePrestataire',
        'nom',
        'contact',
        'email',
        'localisation',
        'userAdd',
        'userUpdate',
        'userDelete',
        'deleted_at',
        'supprimer',

    ];

    public static function disponiblesPourOperateurs()
    {
        return DB::table('prestataires')
            ->select('id', 'libelle', 'codePrestataire')
            ->where('supprimer', 0)
            ->orderBy('libelle')
            ->get();
    }

    public static function existeEtActif(int $id): bool
    {
        return DB::table('prestataires')
            ->where('id', $id)
            ->where('supprimer', 0)
            ->exists();
    }
}

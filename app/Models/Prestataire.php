<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

}

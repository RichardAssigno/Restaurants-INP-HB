<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarteLibre extends Model
{

    use HasFactory;

    protected $table = 'carteslibres';

    protected $fillable = [

        "directions_id",
        "libelle",
        "capacite",
        "dateDebut",
        "nombreJours",
        "userAdd",
        "userUpdate",
        "userDelete",
        "deleted_at",
        "supprimer",

    ];

}

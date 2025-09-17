<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class Operateur extends Authenticatable
{

    use Notifiable;
    use HasRoles;

    protected $table = 'operateurs';

    protected $fillable = [

        'nom',
        'login',
        'prenoms',
        'contact',
        'password',
        'actif',
        'userAdd',
        'userUpdate',
        'userDelete',
        'deleted_at',
        'supprimer',

    ];

    public static function getOperateursAvecRoles()
    {
        return DB::table('operateurs as o')
            ->join('model_has_roles as mhr', 'mhr.model_id', '=', 'o.id')
            ->join('roles as r', 'r.id', '=', 'mhr.role_id')
            ->select(
                'o.id as idOperateur',
                'o.nom',
                'o.prenoms',
                'o.login',
                'o.contact',
                'o.actif',
                'r.id as idRole',
                'r.name',
                'r.guard_name'
            )
            ->where('o.supprimer', '=', 0)
            ->orderBy('o.nom', 'asc')
            ->get();
    }


}

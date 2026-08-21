<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class Operateur extends Authenticatable
{
    use HasRoles;
    use Notifiable;

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
            ->join('model_has_roles as mhr', function ($join) {
                $join->on('mhr.model_id', '=', 'o.id')
                    ->where('mhr.model_type', self::class);
            })
            ->join('roles as r', function ($join) {
                $join->on('r.id', '=', 'mhr.role_id')
                    ->where('r.guard_name', 'operateur');
            })
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

    public static function donneesTableau(array $parametres): array
    {
        $requete = static::requeteGestion();
        $total = (clone $requete)->count('o.id');
        $recherche = trim((string) data_get($parametres, 'search.value', ''));

        if ($recherche !== '') {
            $requete->where(function ($query) use ($recherche) {
                $motif = '%'.$recherche.'%';

                $query->where('o.nom', 'like', $motif)
                    ->orWhere('o.prenoms', 'like', $motif)
                    ->orWhere('o.login', 'like', $motif)
                    ->orWhere('o.contact', 'like', $motif)
                    ->orWhere('p.libelle', 'like', $motif)
                    ->orWhere('p.codePrestataire', 'like', $motif)
                    ->orWhere('r.name', 'like', $motif);
            });
        }

        $filtres = (clone $requete)->count('o.id');
        $colonnesTri = [
            1 => 'o.nom',
            2 => 'o.login',
            3 => 'o.contact',
            4 => 'p.libelle',
            5 => 'o.actif',
        ];
        $colonneDemandee = (int) data_get($parametres, 'order.0.column', 1);
        $direction = strtolower((string) data_get($parametres, 'order.0.dir', 'asc')) === 'desc'
            ? 'desc'
            : 'asc';

        $requete->orderBy($colonnesTri[$colonneDemandee] ?? 'o.nom', $direction)
            ->orderBy('o.prenoms')
            ->orderBy('o.id');

        $debut = max(0, (int) ($parametres['start'] ?? 0));
        $longueurDemandee = (int) ($parametres['length'] ?? 25);
        $longueur = $longueurDemandee === -1
            ? min($filtres, 500)
            : min(max($longueurDemandee, 10), 100);

        $operateurs = $requete
            ->select([
                'o.id',
                'o.nom',
                'o.prenoms',
                'o.login',
                'o.contact',
                'o.actif',
                'p.id as prestataires_id',
                'p.libelle as prestataire',
                'p.codePrestataire',
                'r.id as roles_id',
                'r.name as role',
            ])
            ->offset($debut)
            ->limit($longueur)
            ->get();

        return [
            'draw' => (int) ($parametres['draw'] ?? 0),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtres,
            'data' => $operateurs,
        ];
    }

    public static function trouverPourGestion(int $id): ?object
    {
        return static::requeteGestion()
            ->select([
                'o.id',
                'o.nom',
                'o.prenoms',
                'o.login',
                'o.contact',
                'o.actif',
                'p.id as prestataires_id',
                'p.libelle as prestataire',
                'r.id as roles_id',
                'r.name as role',
            ])
            ->where('o.id', $id)
            ->first();
    }

    public static function loginExiste(string $login, ?int $idIgnore = null): bool
    {
        return DB::table('operateurs')
            ->whereRaw('LOWER(login) = ?', [mb_strtolower(trim($login), 'UTF-8')])
            ->where('supprimer', 0)
            ->when($idIgnore !== null, fn ($query) => $query->where('id', '<>', $idIgnore))
            ->exists();
    }

    public static function contactExiste(string $contact, ?int $idIgnore = null): bool
    {
        return DB::table('operateurs')
            ->where('contact', trim($contact))
            ->where('supprimer', 0)
            ->when($idIgnore !== null, fn ($query) => $query->where('id', '<>', $idIgnore))
            ->exists();
    }

    public static function creerAvecPrestataire(array $donnees, ?int $adminId): int
    {
        return DB::transaction(function () use ($donnees, $adminId) {
            $maintenant = now();
            $operateurId = DB::table('operateurs')->insertGetId([
                'nom' => mb_strtoupper(trim($donnees['nom']), 'UTF-8'),
                'prenoms' => mb_strtoupper(trim($donnees['prenoms']), 'UTF-8'),
                'login' => mb_strtolower(trim($donnees['login']), 'UTF-8'),
                'password' => Hash::make($donnees['password']),
                'contact' => trim($donnees['contact']),
                'actif' => 1,
                'supprimer' => 0,
                'userAdd' => $adminId,
                'created_at' => $maintenant,
                'updated_at' => $maintenant,
            ]);

            static::activerPrestataire($operateurId, (int) $donnees['prestataires_id'], $adminId, true);
            static::attacherRole($operateurId, (int) $donnees['roles_id']);

            return $operateurId;
        });
    }

    public static function modifierAvecPrestataire(int $id, array $donnees, ?int $adminId): bool
    {
        return DB::transaction(function () use ($id, $donnees, $adminId) {
            $modifications = [
                'nom' => mb_strtoupper(trim($donnees['nom']), 'UTF-8'),
                'prenoms' => mb_strtoupper(trim($donnees['prenoms']), 'UTF-8'),
                'login' => mb_strtolower(trim($donnees['login']), 'UTF-8'),
                'contact' => trim($donnees['contact']),
                'userUpdate' => $adminId,
                'updated_at' => now(),
            ];

            if (! empty($donnees['password'])) {
                $modifications['password'] = Hash::make($donnees['password']);
            }

            $modifie = DB::table('operateurs')
                ->where('id', $id)
                ->where('supprimer', 0)
                ->update($modifications);

            if ($modifie === 0 && ! DB::table('operateurs')->where('id', $id)->where('supprimer', 0)->exists()) {
                return false;
            }

            static::activerPrestataire($id, (int) $donnees['prestataires_id'], $adminId, false);
            static::attacherRole($id, (int) $donnees['roles_id']);

            return true;
        });
    }

    public static function supprimerPourGestion(int $id, ?int $adminId): bool
    {
        return DB::transaction(function () use ($id, $adminId) {
            $maintenant = now();
            $supprime = DB::table('operateurs')
                ->where('id', $id)
                ->where('supprimer', 0)
                ->update([
                    'actif' => 0,
                    'supprimer' => 1,
                    'userDelete' => $adminId,
                    'deleted_at' => $maintenant,
                    'updated_at' => $maintenant,
                ]);

            if ($supprime === 0) {
                return false;
            }

            DB::table('operateursprestataires')
                ->where('operateurs_id', $id)
                ->where('supprimer', 0)
                ->update([
                    'statut' => 1,
                    'supprimer' => 1,
                    'dateFin' => $maintenant,
                    'userDelete' => $adminId,
                    'deleted_at' => $maintenant,
                    'updated_at' => $maintenant,
                ]);

            DB::table('model_has_roles')
                ->where('model_type', static::class)
                ->where('model_id', $id)
                ->delete();

            return true;
        });
    }

    public static function basculerStatut(int $id, ?int $adminId): ?bool
    {
        return DB::transaction(function () use ($id, $adminId) {
            $operateur = DB::table('operateurs')
                ->select('id', 'actif')
                ->where('id', $id)
                ->where('supprimer', 0)
                ->lockForUpdate()
                ->first();

            if (! $operateur) {
                return null;
            }

            $nouveauStatut = ! (bool) $operateur->actif;

            DB::table('operateurs')
                ->where('id', $id)
                ->update([
                    'actif' => (int) $nouveauStatut,
                    'userUpdate' => $adminId,
                    'updated_at' => now(),
                ]);

            return $nouveauStatut;
        });
    }

    public static function rolesDisponibles()
    {
        return DB::table('roles')
            ->select('id', 'name')
            ->where('guard_name', 'operateur')
            ->orderBy('name')
            ->get();
    }

    public static function roleOperateurExiste(int $id): bool
    {
        return DB::table('roles')
            ->where('id', $id)
            ->where('guard_name', 'operateur')
            ->exists();
    }

    private static function requeteGestion()
    {
        $dernieresLiaisonsActives = DB::table('operateursprestataires')
            ->selectRaw('MAX(id) as id, operateurs_id')
            ->where('supprimer', 0)
            ->where('statut', 0)
            ->whereNull('dateFin')
            ->groupBy('operateurs_id');

        $rolesOperateurs = DB::table('model_has_roles as mr_source')
            ->join('roles as r_source', function ($join) {
                $join->on('r_source.id', '=', 'mr_source.role_id')
                    ->where('r_source.guard_name', 'operateur');
            })
            ->selectRaw('MAX(mr_source.role_id) as role_id, mr_source.model_id')
            ->where('mr_source.model_type', static::class)
            ->groupBy('mr_source.model_id');

        return DB::table('operateurs as o')
            ->leftJoinSub($dernieresLiaisonsActives, 'op_active', function ($join) {
                $join->on('op_active.operateurs_id', '=', 'o.id');
            })
            ->leftJoin('operateursprestataires as op', 'op.id', '=', 'op_active.id')
            ->leftJoin('prestataires as p', function ($join) {
                $join->on('p.id', '=', 'op.prestataires_id')
                    ->where('p.supprimer', 0);
            })
            ->leftJoinSub($rolesOperateurs, 'mr', function ($join) {
                $join->on('mr.model_id', '=', 'o.id');
            })
            ->leftJoin('roles as r', function ($join) {
                $join->on('r.id', '=', 'mr.role_id')
                    ->where('r.guard_name', 'operateur');
            })
            ->where('o.supprimer', 0);
    }

    private static function attacherRole(int $operateurId, int $roleId): void
    {
        DB::table('model_has_roles')
            ->where('model_type', static::class)
            ->where('model_id', $operateurId)
            ->delete();

        DB::table('model_has_roles')->insert([
            'role_id' => $roleId,
            'model_type' => static::class,
            'model_id' => $operateurId,
        ]);
    }

    private static function activerPrestataire(
        int $operateurId,
        int $prestataireId,
        ?int $adminId,
        bool $creation
    ): void {
        $maintenant = now();

        $liaison = DB::table('operateursprestataires')
            ->where('operateurs_id', $operateurId)
            ->where('prestataires_id', $prestataireId)
            ->orderByDesc('id')
            ->first();

        DB::table('operateursprestataires')
            ->where('operateurs_id', $operateurId)
            ->where('supprimer', 0)
            ->where('statut', 0)
            ->update([
                'statut' => 1,
                'dateFin' => $maintenant,
                'userUpdate' => $adminId,
                'updated_at' => $maintenant,
            ]);

        if ($liaison) {
            DB::table('operateursprestataires')
                ->where('id', $liaison->id)
                ->update([
                    'dateDebut' => $liaison->dateDebut ?: $maintenant,
                    'dateFin' => null,
                    'statut' => 0,
                    'supprimer' => 0,
                    'userUpdate' => $creation ? null : $adminId,
                    'userDelete' => null,
                    'deleted_at' => null,
                    'updated_at' => $maintenant,
                ]);

            return;
        }

        DB::table('operateursprestataires')->insert([
            'operateurs_id' => $operateurId,
            'prestataires_id' => $prestataireId,
            'dateDebut' => $maintenant,
            'dateFin' => null,
            'statut' => 0,
            'supprimer' => 0,
            'userAdd' => $adminId,
            'created_at' => $maintenant,
            'updated_at' => $maintenant,
        ]);
    }

    public static function getInfoOperateur($idOperateur)
    {
        return DB::table('operateurs as o')
            ->join('operateursprestataires as op', function ($join) {
                $join->on('op.operateurs_id', '=', 'o.id')
                    ->where('op.supprimer', 0)
                    ->where('op.statut', 0)
                    ->whereNull('op.dateFin');
            })
            ->join('prestataires as p', function ($join) {
                $join->on('p.id', '=', 'op.prestataires_id')
                    ->where('p.supprimer', 0);
            })
            ->select(
                'o.id as idOperateur',
                'o.nom',
                'o.prenoms',
                'o.login',
                'p.id as idPrestataire',
                'p.libelle as libellePrestataire',
                'p.codePrestataire'
            )
            ->where('o.id', $idOperateur)
            ->where('o.supprimer', 0)
            ->first();
    }
}

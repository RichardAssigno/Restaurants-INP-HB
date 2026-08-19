<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    private const GUARD = 'operateur';

    public function index()
    {

        return view('utilisateurs.roles.index', [

            'roles' => Role::query()->where('guard_name', '=', self::GUARD)->orderBy('name', 'asc')->get(),
            'title' => 'Mes roles',

        ]);

    }

    public function ajouter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                Rule::unique('roles', 'name')->where('guard_name', self::GUARD),
            ],
        ], [
            'name.required' => 'Le nom du rôle est obligatoire.',
            'name.unique' => 'Le role doit être unique.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        Role::create([
            'name' => $data['name'],
            'guard_name' => self::GUARD,
        ]);

        return response()->json([
            'success' => 'Enregistrement effectué avec succès',
        ], 200);

    }

    public function recuperer($id)
    {

        $role = Role::query()->where('guard_name', self::GUARD)->findOrFail($id);

        return response()->json($role);

    }

    public function rolestoutrecuperer()
    {

        $role = Role::query()->where('guard_name', '=', self::GUARD)->orderBy('name', 'asc')->get();

        return response()->json($role);

    }

    public function modifier(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'libelle' => [
                'required',
                'string',
                Rule::unique('roles', 'name')
                    ->where('guard_name', self::GUARD)
                    ->ignore($request->id),
            ],
            'id' => ['required'],

        ], [

            'libelle.required' => 'Veillez entrer un nom pour votre autorisation',
            'libelle.unique' => 'Cette Autorisations existe déjà dans la base de donnée',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        $role = Role::query()->where('guard_name', self::GUARD)->findOrFail($data['id']);

        $dataRole = [
            'name' => $data['libelle'],
        ];

        $role->update($dataRole);

        return response()->json([
            'status' => 'success',
            'message' => 'Rôle modifiée avec succès',
            'permission' => $role,
        ]);

    }

    public function supprimer($id)
    {

        $role = Role::query()->where('guard_name', self::GUARD)->findOrFail($id);

        $role->delete();

        return response()->json(['message' => 'Entrée supprimée avec succès']);
    }

    public function chargerpermissions($id)
    {

        $role = Role::query()->where('guard_name', self::GUARD)->findOrFail($id);

        $permissions = Permission::query()->where('guard_name', '=', self::GUARD)->orderBy('name', 'asc')->get()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0]; // Rubrique = partie avant le point
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return response()->json([
            'grouped' => $permissions,
            'assigned' => $rolePermissions,
        ]);
    }

    public function ajouterpermissions(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => [
                'string',
                Rule::exists('permissions', 'name')->where('guard_name', self::GUARD),
            ],
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->where('guard_name', self::GUARD),
            ],
        ], [
            'permissions.required' => 'Veuillez cocher des permissions pour votre rôle',
            'permissions.array' => 'Les permissions doivent être un tableau',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        $role = Role::query()->where('guard_name', self::GUARD)->findOrFail($data['role_id']);

        $existingPermissions = $role->permissions->pluck('name')->toArray();
        $newPermissions = $data['permissions'] ?? [];

        // Tri pour comparaison
        sort($existingPermissions);
        sort($newPermissions);

        if ($existingPermissions === $newPermissions) {
            return response()->json([
                'errors' => [
                    'global' => ['Pas de modification effectuée'],
                ],
            ], 422);
        }

        // Si on a retiré des permissions → reset + assignation
        if (count($newPermissions) < count($existingPermissions)) {
            $role->syncPermissions($newPermissions);

            return response()->json(['message' => 'Permissions mises à jour (modification détectée)']);
        }

        // Sinon on ajoute uniquement les nouvelles
        $permissionsToAdd = array_diff($newPermissions, $existingPermissions);

        if (count($permissionsToAdd)) {
            $role->givePermissionTo($permissionsToAdd);

            return response()->json(['message' => 'Nouvelles autorisations ajoutées avec succès']);
        }

        return response()->json([
            'errors' => [
                'global' => ['Pas de modification effectuée'],
            ],
        ], 422);

    }

    public function recherche(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'recherche' => ['required', 'string'],
        ], [
            'recherche.required' => 'Le champ recherche est obligatoire',
            'recherche.string' => 'La recherche doit être de type chaîne de caractère',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        $permissions = Permission::query()
            ->where('guard_name', self::GUARD)
            ->where('name', 'like', '%'.$data['recherche'].'%')
            ->orderBy('name')
            ->limit(25)
            ->get();

        return response()->json(['permissions' => $permissions]);

    }
}

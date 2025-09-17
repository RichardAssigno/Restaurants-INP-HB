<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{

    public function index(){

        return view('utilisateurs.roles.index', [

            "roles" => Role::query()->where("guard_name", "=", "operateur")->orderBy('name', 'asc')->get(),
            "title" => "Mes roles",

        ]);

    }

    public function ajouter(Request $request)
    {
        //unique:roles,name
        $role = Role::query()->where("name", "=", $request->name)->first();

        $verif = ($role && $role->guard_name === "operateur") ? "unique:roles,name" : "";

        $validator = Validator::make($request->all(),[
            'name' => "required|" . $verif,
        ],[
            'name.required' => 'Le nom du rôle est obligatoire.',
            'name.unique' => 'Le role doit être unique.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        Role::create([
            'name' => $data['name'],
        ]);

        return response()->json([
            'success' => "Enregistrement effectué avec succès"
        ], 200);

    }

    public function recuperer($id){

        $role = Role::query()->findOrFail($id);
        return response()->json($role);

    }
    public function rolestoutrecuperer(){

        $role = Role::query()->where("guard_name", "=", "operateur")->orderBy('name', 'asc')->get();
        return response()->json($role);

    }

    public function modifier(Request $request){

        $validator = Validator::make($request->all(), [

            'libelle' => ['required', 'string'],
            'id' => ['required']

        ], [

            "libelle.required" => "Veillez entrer un nom pour votre autorisation",
            "libelle.unique" => "Cette Autorisations existe déjà dans la base de donnée",

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        $role = Role::query()->findOrFail($data['id']);

        $dataRole = [
            "name" => $data['libelle'],
        ];

        $role->update($dataRole);

        return response()->json([
            'status' => 'success',
            'message' => 'Rôle modifiée avec succès',
            'permission' => $role
        ]);

    }

    public function supprimer($id){

        $role = Role::query()->findOrFail($id);

        $role->delete();

        return response()->json(['message' => 'Entrée supprimée avec succès']);
    }

    public function chargerpermissions($id)
    {

        $role = Role::query()->findOrFail($id);

        $permissions =  Permission::query()->where("guard_name" , "=", "operateur")->orderBy("name", "asc")->get()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0]; // Rubrique = partie avant le point
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return response()->json([
            'grouped' => $permissions,
            'assigned' => $rolePermissions
        ]);
    }

    public function ajouterpermissions(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'permissions' => ['required', 'array'],
            'role_id' => ['required']
        ], [
            "permissions.required" => "Veuillez cocher des permissions pour votre rôle",
            "permissions.array" => "Les permissions doivent être un tableau",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        $role = Role::findOrFail($data['role_id']);

        $existingPermissions = $role->permissions->pluck('name')->toArray();
        $newPermissions = $data['permissions'];

        // Tri pour comparaison
        sort($existingPermissions);
        sort($newPermissions);

        if ($existingPermissions === $newPermissions) {
            return response()->json([
                'errors' => [
                    'global' => ['Pas de modification effectuée']
                ]
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
                'global' => ['Pas de modification effectuée']
            ]
        ], 422);

    }

    public function recherche(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'recherche' => ['required', 'string'],
        ], [
            "recherche.required" => "Le champ recherche est obligatoire",
            "recherche.string" => "La recherche doit être de type chaîne de caractère",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        $candidats = Candidat::rechercherRendezvousdunCandidat($data['recherche']);

        return response()->json(['candidats' => $candidats]);

    }

}

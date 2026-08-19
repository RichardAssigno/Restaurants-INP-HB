<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    private const GUARD = 'operateur';

    public function index()
    {

        return view('utilisateurs.permissions.index', [

            'permissions' => Permission::query()->where('guard_name', '=', self::GUARD)->orderBy('name', 'asc')->get(),

        ]);

    }

    public function ajouter(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'libelle' => [
                'required',
                'string',
                Rule::unique('permissions', 'name')->where('guard_name', self::GUARD),
            ],
        ], [

            'libelle.required' => 'Veillez entrer un nom pour votre autorisation',
            'libelle.unique' => 'Cette Autorisations existe déjà dans la base de donnée',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Permission::create([
            'name' => $request->libelle,
            'guard_name' => self::GUARD,
        ]);

        return response()->json([
            'success' => 'Enregistrement effectué avec succès',
        ], 200);

    }

    public function recuperer($id)
    {

        $permission = Permission::query()->where('guard_name', self::GUARD)->findOrFail($id);

        return response()->json($permission);

    }

    public function modifier(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'libelle' => [
                'required',
                'string',
                Rule::unique('permissions', 'name')
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

        $permission = Permission::query()->where('guard_name', self::GUARD)->findOrFail($data['id']);

        $dataPermission = [
            'name' => $data['libelle'],
        ];

        $permission->update($dataPermission);

        return response()->json([
            'status' => 'success',
            'message' => 'Permission modifiée avec succès',
            'permission' => $permission,
        ]);

    }

    public function supprimer($id)
    {

        $permission = Permission::query()->where('guard_name', self::GUARD)->findOrFail($id);

        $permission->delete();

        return response()->json(['message' => 'Entrée supprimée avec succès']);
    }
}

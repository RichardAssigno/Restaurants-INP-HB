<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{

    public function index()
    {

        dd(Permission::all());

        return view('utilisateurs.permissions.index', [

            "permissions" => Permission::query()->where("guard_name" , "=", "operateur")->orderBy("name", "asc")->get()

        ]);

    }

    public function ajouter(Request $request){

        $validator = Validator::make($request->all(), [

            'libelle' => [
                'required',
                'string',
                'unique:permissions,name'
            ]
        ], [

            "libelle.required" => "Veillez entrer un nom pour votre autorisation",
            "libelle.unique" => "Cette Autorisations existe déjà dans la base de donnée",

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Permission::create([
            'name' => $request->libelle
        ]);

        return response()->json([
            'success' => "Enregistrement effectué avec succès"
        ], 200);


    }

    public function recuperer($id){

        $permission = Permission::query()->findOrFail($id);
        return response()->json($permission);

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

        $permission = Permission::query()->findOrFail($data['id']);

        $dataPermission = [
            "name" => $data['libelle'],
        ];

        $permission->update($dataPermission);

        return response()->json([
            'status' => 'success',
            'message' => 'Permission modifiée avec succès',
            'permission' => $permission
        ]);

    }

    public function supprimer($id){

        $permission = Permission::query()->findOrFail($id);

        $permission->delete();

        return response()->json(['message' => 'Entrée supprimée avec succès']);
    }

}

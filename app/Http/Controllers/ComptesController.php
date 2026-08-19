<?php

namespace App\Http\Controllers;

use App\Models\Operateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class ComptesController extends Controller
{
    public function index()
    {

        return view('utilisateurs.comptes.index', [

            'roles' => Role::query()->where('guard_name', '=', 'operateur')->orderBy('name', 'asc')->get(),
            'operateurs' => Operateur::getOperateursAvecRoles(),

        ]);

    }

    public function ajouter(Request $request)
    {
        $operateur = Operateur::query()->where('login', '=', $request->login)
            ->where('contact', '=', $request->telephone)
            ->first();

        $verif = $operateur && $operateur->nom == mb_strtoupper($request->nom) && $operateur->prenoms == mb_strtoupper($request->prenoms) && $operateur->contact == mb_strtoupper($request->telephone);

        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string'],
            'prenoms' => ['required', 'string'],
            'login' => ['required', 'string', $verif ? Rule::unique('operateurs', 'login')->ignore($operateur->id) : Rule::unique('operateurs', 'login')],
            'password' => ['required', 'string', 'min:8'],
            'telephone' => ['required', 'string', $verif ? Rule::unique('operateurs', 'contact')->ignore($operateur->id) : Rule::unique('operateurs', 'contact')],
            'role' => ['required', Rule::exists('roles', 'id')->where('guard_name', 'operateur')],
        ], [
            'nom.required' => 'Le nom est obligatoire',
            'nom.string' => 'Le nom doit être de type chaîne de caractère',
            'prenoms.required' => 'Le prenoms est obligatoire',
            'prenoms.string' => 'Le prenoms doit être de type chaîne de caractère',
            'login.required' => 'Le login est obligatoire',
            'login.string' => 'Le login doit être de type chaîne de caractère',
            'login.unique' => 'Ce Login est déjà utilisé.',
            'telephone.required' => 'Le telephone est obligatoire',
            'telephone.string' => 'Le telephone doit être de type chaîne de caractère',
            'telephone.unique' => 'Ce numéro de telephone est déjà utilisé',
            'role.required' => 'Veillez selectionner un role',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        $role = Role::query()->where('guard_name', 'operateur')->findOrFail($data['role']);

        if ($verif) {

            $operateur->update([

                'supprimer' => 0,
                'userUpdate' => Auth::guard('operateur')->id(),

            ]);

            $operateur->syncRoles($role);

            return response()->json([
                'success' => true,
                'message' => 'Compte ajouté avec succès',
            ]);

        }

        $dataOperateurs = [

            'nom' => mb_strtoupper($data['nom']),
            'prenoms' => mb_strtoupper($data['prenoms']),
            'contact' => $data['telephone'],
            'login' => $data['login'],
            'password' => Hash::make($data['password']),
            'userAdd' => Auth::guard('operateur')->id(),

        ];

        $operateur = Operateur::query()->create($dataOperateurs);

        $operateur->syncRoles($role);

        return response()->json([
            'success' => true,
            'message' => 'Compte ajouté avec succès',
        ]);

    }

    public function recuperer($id)
    {

        $admin = Operateur::getOperateursAvecRoles()->firstWhere('idOperateur', (int) $id);

        if (! $admin) {
            return response()->json(['message' => 'Compte introuvable.'], 404);
        }

        return response()->json($admin);

    }

    public function modifier(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'operateur_id' => ['required', 'integer', 'exists:operateurs,id'],
            'nom' => ['required', 'string'],
            'prenoms' => ['required', 'string'],
            'login' => ['required', 'string', Rule::unique('operateurs', 'login')->ignore($request->operateur_id)],
            'password' => ['nullable', 'string', 'min:8'], // facultatif pour update
            'telephone' => ['required', 'string', Rule::unique('operateurs', 'contact')->ignore($request->operateur_id)],
            'role' => ['required', Rule::exists('roles', 'id')->where('guard_name', 'operateur')],
        ], [
            'operateur_id.required' => "L'id de l'Admin est obligatoire",
            'nom.required' => 'Le nom est obligatoire',
            'nom.string' => 'Le nom doit être de type chaîne de caractère',
            'prenoms.required' => 'Le prenoms est obligatoire',
            'prenoms.string' => 'Le prenoms doit être de type chaîne de caractère',
            /* "email.required" => "L'email est obligatoire",
            "email.string" => "L'email doit être de type chaîne de caractère",
            "email.unique" => "L'email doit être unique",*/
            'telephone.required' => 'Le téléphone est obligatoire',
            'telephone.string' => 'Le téléphone doit être de type chaîne de caractère',
            'role.required' => 'Veuillez sélectionner un rôle',
            'role.exists' => "Le rôle n'existe pas",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        $role = Role::query()->where('guard_name', 'operateur')->findOrFail($data['role']);

        $admin = Operateur::query()->findOrFail($data['operateur_id']);

        $dataAdmin = [
            'nom' => mb_strtoupper($data['nom']),
            'prenoms' => mb_strtoupper($data['prenoms']),
            'login' => $data['login'],
            'contact' => $data['telephone'],
            'userUpdate' => Auth::guard('operateur')->id(),
        ];

        if ($request->filled('password')) {
            $dataAdmin['password'] = Hash::make($data['password']);
        }

        $admin->update($dataAdmin);

        $admin->syncRoles($role);

        return response()->json([
            'status' => 'success',
            'message' => 'Compte modifiée avec succès',
        ]);

    }

    public function supprimer($id)
    {

        $operateur = Operateur::query()->findOrFail($id);

        $dataOperateur = [

            'supprimer' => 1,
            'userDelete' => Auth::guard('operateur')->id(),

        ];

        $operateur->update($dataOperateur);

        return response()->json(['message' => 'Entrée supprimée avec succès']);
    }

    public function desactiver($id)
    {

        if (Auth::guard('operateur')->id() == $id) {

            return response()->json([
                'success' => false,
                'message' => 'Vous êtes actuellement connectés à se compte, vous ne pouvez pas le désactiver',
            ], 422);

        }

        $operateur = Operateur::query()->findOrFail($id);

        $operateur->update([

            'actif' => 0,
            'userUpdate' => Auth::guard('operateur')->id(),

        ]);

        return response()->json(['message' => 'Compte désactivé avec succès']);

    }

    public function activer($id)
    {

        $operateur = Operateur::query()->findOrFail($id);

        $operateur->update([

            'actif' => 1,
            'userUpdate' => Auth::guard('operateur')->id(),

        ]);

        return response()->json(['message' => 'Compte activé avec succès']);

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Operateur;
use App\Models\Prestataire;
use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;
use Illuminate\View\View;

class ComptesController extends Controller
{
    public function index(): View
    {
        return view('utilisateurs.comptes.index', [
            'prestataires' => Prestataire::disponiblesPourOperateurs(),
            'roles' => Operateur::rolesDisponibles(),
        ]);
    }

    public function donnees(Request $request): JsonResponse
    {
        return response()->json(Operateur::donneesTableau($request->all()));
    }

    public function recuperer(int $id): JsonResponse
    {
        $operateur = Operateur::trouverPourGestion($id);

        if (! $operateur) {
            return response()->json(['message' => "L'administrateur demandé n'existe plus."], 404);
        }

        return response()->json(['data' => $operateur]);
    }

    public function ajouter(Request $request): JsonResponse
    {
        $validateur = $this->validateur($request, null, true);

        if ($validateur->fails()) {
            return response()->json([
                'message' => 'Veuillez corriger les informations du formulaire.',
                'errors' => $validateur->errors(),
            ], 422);
        }

        $operateurId = Operateur::creerAvecPrestataire(
            $validateur->validated(),
            Auth::guard('operateur')->id()
        );

        return response()->json([
            'success' => true,
            'message' => "L'opérateur a été ajouté avec succès.",
            'data' => Operateur::trouverPourGestion($operateurId),
        ], 201);
    }

    public function modifier(Request $request, int $id): JsonResponse
    {
        if (! Operateur::trouverPourGestion($id)) {
            return response()->json(['message' => "L'administrateur demandé n'existe plus."], 404);
        }

        $validateur = $this->validateur($request, $id, false);

        if ($validateur->fails()) {
            return response()->json([
                'message' => 'Veuillez corriger les informations du formulaire.',
                'errors' => $validateur->errors(),
            ], 422);
        }

        Operateur::modifierAvecPrestataire(
            $id,
            $validateur->validated(),
            Auth::guard('operateur')->id()
        );

        return response()->json([
            'message' => "L'administrateur a été modifié avec succès.",
            'data' => Operateur::trouverPourGestion($id),
        ]);
    }

    public function supprimer(int $id): JsonResponse
    {
        if ((int) Auth::guard('operateur')->id() === $id) {
            return response()->json([
                'message' => 'Vous ne pouvez pas supprimer le compte actuellement connecté.',
            ], 422);
        }

        if (! Operateur::supprimerPourGestion($id, Auth::guard('operateur')->id())) {
            return response()->json(['message' => "L'administrateur demandé n'existe plus."], 404);
        }

        return response()->json(['message' => "L'administrateur a été supprimé avec succès."]);
    }

    public function basculerStatut(int $id): JsonResponse
    {
        if ((int) Auth::guard('operateur')->id() === $id) {
            return response()->json([
                'message' => 'Vous ne pouvez pas désactiver le compte actuellement connecté.',
            ], 422);
        }

        $actif = Operateur::basculerStatut($id, Auth::guard('operateur')->id());

        if ($actif === null) {
            return response()->json(['message' => "L'administrateur demandé n'existe plus."], 404);
        }

        return response()->json([
            'message' => $actif
                ? 'Le compte administrateur a été activé avec succès.'
                : 'Le compte administrateur a été désactivé avec succès.',
            'actif' => $actif,
        ]);
    }

    public function reinitialiserMotDePasse(Request $request, int $id, SmsService $smsService): JsonResponse
    {
        $validateur = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
            'notifier_sms' => ['nullable', 'boolean'],
        ], [
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.max' => 'Le mot de passe ne doit pas dépasser 255 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'notifier_sms.boolean' => "Le choix de notification SMS n'est pas valide.",
        ]);

        if ($validateur->fails()) {
            return response()->json([
                'message' => 'Veuillez corriger les informations du formulaire.',
                'errors' => $validateur->errors(),
            ], 422);
        }

        $operateur = Operateur::query()
            ->where('supprimer', 0)
            ->find($id);

        if (! $operateur) {
            return response()->json(['message' => "L'administrateur demandé n'existe plus."], 404);
        }

        $password = $validateur->validated()['password'];

        $operateur->update([
            'password' => Hash::make($password),
            'userUpdate' => Auth::guard('operateur')->id(),
        ]);

        if (! $request->boolean('notifier_sms')) {
            return response()->json([
                'message' => "Le mot de passe de l'administrateur a été réinitialisé sans notification SMS.",
                'sms_envoye' => null,
            ]);
        }

        $telephone = $smsService->formatRecipient($operateur->contact);

        if ($telephone === null) {
            return response()->json([
                'message' => "Le mot de passe a été réinitialisé, mais aucun numéro de téléphone valide ne permet d'envoyer le SMS.",
                'sms_envoye' => false,
            ]);
        }

        $message = "Votre mot de passe administrateur a été réinitialisé. Identifiant : {$operateur->login}. Nouveau mot de passe : {$password}. Lien de connexion : ".route('login');

        try {
            $response = $smsService->send($telephone, $message);
            $smsEnvoye = $smsService->isSuccessful($response);
        } catch (\Throwable $exception) {
            Log::error("L'envoi du SMS de réinitialisation administrateur a échoué.", [
                'operateur_id' => $operateur->id,
                'exception' => $exception->getMessage(),
            ]);
            $smsEnvoye = false;
        }

        return response()->json([
            'message' => $smsEnvoye
                ? "Le mot de passe a été réinitialisé et envoyé à l'administrateur par SMS."
                : "Le mot de passe a été réinitialisé, mais l'envoi du SMS a échoué.",
            'sms_envoye' => $smsEnvoye,
        ]);
    }

    private function validateur(Request $request, ?int $id, bool $creation): ValidationValidator
    {
        $reglesMotDePasse = $creation
            ? ['required', 'string', 'min:8', 'max:255', 'confirmed']
            : ['nullable', 'string', 'min:8', 'max:255', 'confirmed'];

        $validateur = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255'],
            'prenoms' => ['required', 'string', 'max:255'],
            'login' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9._-]+$/'],
            'password' => $reglesMotDePasse,
            'contact' => ['required', 'string', 'max:255'],
            'roles_id' => ['required', 'integer'],
            'prestataires_id' => ['required', 'integer'],
        ], [
            'required' => 'Le champ :attribute est obligatoire.',
            'login.regex' => 'Le login accepte uniquement les lettres non accentuées, chiffres, points, tirets et tirets bas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'roles_id.integer' => 'Le rôle sélectionné est invalide.',
            'prestataires_id.integer' => 'Le prestataire sélectionné est invalide.',
        ], [
            'nom' => 'nom',
            'prenoms' => 'prénoms',
            'login' => 'login',
            'password' => 'mot de passe',
            'contact' => 'contact',
            'roles_id' => 'rôle',
            'prestataires_id' => 'prestataire',
        ]);

        $validateur->after(function (ValidationValidator $validateur) use ($request, $id) {
            if (! $validateur->errors()->has('login')
                && $request->filled('login')
                && Operateur::loginExiste($request->string('login')->toString(), $id)) {
                $validateur->errors()->add('login', 'Ce login est déjà utilisé par un administrateur.');
            }

            if (! $validateur->errors()->has('contact')
                && $request->filled('contact')
                && Operateur::contactExiste($request->string('contact')->toString(), $id)) {
                $validateur->errors()->add('contact', 'Ce contact est déjà utilisé par un administrateur.');
            }

            if (! $validateur->errors()->has('prestataires_id')
                && $request->filled('prestataires_id')
                && ! Prestataire::existeEtActif((int) $request->input('prestataires_id'))) {
                $validateur->errors()->add('prestataires_id', "Le prestataire sélectionné n'est pas disponible.");
            }

            if (! $validateur->errors()->has('roles_id')
                && $request->filled('roles_id')
                && ! Operateur::roleOperateurExiste((int) $request->input('roles_id'))) {
                $validateur->errors()->add('roles_id', "Le rôle sélectionné n'est pas disponible.");
            }
        });

        return $validateur;
    }
}

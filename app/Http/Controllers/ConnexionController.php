<?php

namespace App\Http\Controllers;

use App\Models\Loginimages;
use App\Models\Logintextes;
use App\Models\Operateur;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class ConnexionController extends Controller
{

    public function index()
    {

        return view('login.index', [

            'title' => 'Connexion',
            'texte' => Logintextes::getTexte(),
            'image' => Loginimages::getImage(),

        ]);
    }

    public function connexion(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required|min:8',
        ], [
            'login.required' => 'Le Login est obligatoire',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Il faut au moins 8 caractères pour le mot de passe',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validate();

        $operateur = Operateur::query()->where('login', '=', $data['login'])->first();

        if ($operateur->actif === 1){

            $credentials = $request->only('login', 'password');

            $remember = $request->filled('remember');

            if (Auth::guard('operateur')->attempt($credentials)) {

                $request->session()->regenerate();

                $redirectUrl = redirect()->intended(route('tableaudebord.index'))->getTargetUrl();

                return response()->json([
                    'success' => 'Connexion réussie',
                    'redirect' => $redirectUrl
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects'
            ], 422);

        }

        return response()->json([
            'success' => false,
            'message' => 'Votre compte n\'est pas activé. Veillez contacter le service informatique.'
        ], 422);

    }

    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route("login");
    }

}

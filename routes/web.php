<?php

use App\Http\Controllers\ComptesController;
use App\Http\Controllers\ComptesRestauxController;
use App\Http\Controllers\ConnexionController;
use App\Http\Controllers\FacturationController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RechercherController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TableaudebordController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [ConnexionController::class, 'index'])->name('login');
Route::post('/connexion-utilisateur', [ConnexionController::class, 'connexion'])->name('connexion');

Route::middleware('auth:operateur')->group(function () {

    Route::get('/', [TableaudebordController::class, 'index'])->name('tableaudebord.index');
    Route::post('/tableau-de-bord', [TableaudebordController::class, 'recuperer'])->name('tableaudebord.recuperer');
    Route::post('se-déconnecter', [ConnexionController::class, 'logout'])->name('logout');

    Route::get('/bilan-en-pdf', [TableaudebordController::class, 'pdf'])->name('bilan.pdf');

    Route::get('/search', [RechercherController::class, 'rechercher'])->name('rechercher.etudiants');
    Route::get('/search/affiche-etudiant/{id}', [RechercherController::class, 'afficher'])->name('afficher.etudiants');

    Route::get('/Roles', [RolesController::class, 'index'])->name('roles.index');
    Route::post('/Roles-Ajouter', [RolesController::class, 'ajouter'])->name('roles.ajouter');
    Route::post('/Roles-Modifier', [RolesController::class, 'modifier'])->name('roles.modifier');
    Route::get('/Roles-Recuperer/{id}', [RolesController::class, 'recuperer'])->name('roles.recuperer');
    Route::get('/Roles-Tout-Recuperer', [RolesController::class, 'rolestoutrecuperer'])->name('roles.rolestoutrecuperer');
    Route::delete('/Supprimer-Roles/{id}', [RolesController::class, 'supprimer'])->name('roles.supprimer');
    Route::get('/Roles-Charger-Permissions/{id}', [RolesController::class, 'chargerpermissions'])->name('roles.chargerpermissions');
    Route::post('/Roles-Ajouter-Permissions', [RolesController::class, 'ajouterpermissions'])->name('roles.ajouterpermissions');
    Route::post('/Roles-Rechercher-Permissions', [RolesController::class, 'recherche'])->name('roles.recherche');

    Route::get('/Permissions', [PermissionsController::class, 'index'])->name('permissions.index');
    Route::post('/Permissions-Ajout', [PermissionsController::class, 'ajouter'])->name('permissions.ajouter');
    Route::get('/Permissions-Recuperer/{id}', [PermissionsController::class, 'recuperer'])->name('permissions.permissionsrecuperer');
    Route::post('/Permissions-Modifier', [PermissionsController::class, 'modifier'])->name('permissions.modifier');
    Route::delete('/Supprimer-Permission/{id}', [PermissionsController::class, 'supprimer'])->name('permissions.supprimer');

    Route::get('/comptes', [ComptesController::class, 'index'])->name('comptes.index');
    Route::post('/Ajouter-Comptes', [ComptesController::class, 'ajouter'])->name('comptes.ajouter');
    Route::post('/Modifier-Comptes', [ComptesController::class, 'modifier'])->name('comptes.modifier');
    Route::delete('/comptes-supprimer/{id}', [ComptesController::class, 'supprimer'])->name('comptes.supprimer');
    Route::get('/desactiver-compte/{id}', [ComptesController::class, 'desactiver'])->name('comptes.desactiver');
    Route::get('/activer-compte/{id}', [ComptesController::class, 'activer'])->name('comptes.activer');


    Route::get('/compte-restaurant', [ComptesRestauxController::class, 'index'])->name('compterestau.index');
    Route::post('/ajouter-compte-restaurant', [ComptesRestauxController::class, 'ajouter'])->name('compterestau.ajouter');


    Route::get('/Facturations', [FacturationController::class, 'index'])->name('facturations.index');
    Route::post('/Facturations-Sacn-Qr-Code', [FacturationController::class, 'scanqrcode'])->name('facturations.scanqrcode');
    Route::get('/Facturations-refresh', [FacturationController::class, 'refresh'])->name('facturations.refresh');

});

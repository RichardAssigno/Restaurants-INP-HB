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
    Route::get('/search/affiche-etudiant/{id?}', [RechercherController::class, 'afficher'])->name('afficher.etudiants');
    Route::get('/search/affiche-compte-libre/{id}', [RechercherController::class, 'afficherCompteLibre'])
        ->whereNumber('id')
        ->name('afficher.comptes-libres');

    Route::middleware('permission:Utilisateurs.Voir les Roles')->group(function () {
        Route::get('/Roles', [RolesController::class, 'index'])->name('roles.index');
        Route::post('/Roles-Ajouter', [RolesController::class, 'ajouter'])->name('roles.ajouter');
        Route::post('/Roles-Modifier', [RolesController::class, 'modifier'])->name('roles.modifier');
        Route::get('/Roles-Recuperer/{id}', [RolesController::class, 'recuperer'])->name('roles.recuperer');
        Route::delete('/Supprimer-Roles/{id}', [RolesController::class, 'supprimer'])->name('roles.supprimer');
        Route::get('/Roles-Charger-Permissions/{id}', [RolesController::class, 'chargerpermissions'])->name('roles.chargerpermissions');
        Route::post('/Roles-Ajouter-Permissions', [RolesController::class, 'ajouterpermissions'])->name('roles.ajouterpermissions');
        Route::post('/Roles-Rechercher-Permissions', [RolesController::class, 'recherche'])->name('roles.recherche');
    });

    Route::get('/Roles-Tout-Recuperer', [RolesController::class, 'rolestoutrecuperer'])
        ->middleware('permission:Utilisateurs.Voir les Administrateurs|Utilisateurs.Voir les Roles')
        ->name('roles.rolestoutrecuperer');

    Route::middleware('permission:Utilisateurs.Voir les Permissions')->group(function () {
        Route::get('/Permissions', [PermissionsController::class, 'index'])->name('permissions.index');
        Route::post('/Permissions-Ajout', [PermissionsController::class, 'ajouter'])->name('permissions.ajouter');
        Route::get('/Permissions-Recuperer/{id}', [PermissionsController::class, 'recuperer'])->name('permissions.permissionsrecuperer');
        Route::post('/Permissions-Modifier', [PermissionsController::class, 'modifier'])->name('permissions.modifier');
        Route::delete('/Supprimer-Permission/{id}', [PermissionsController::class, 'supprimer'])->name('permissions.supprimer');
    });

    Route::middleware('permission:Utilisateurs.Voir les Administrateurs')->group(function () {
        Route::get('/comptes', [ComptesController::class, 'index'])->name('comptes.index');
        Route::get('/comptes/donnees', [ComptesController::class, 'donnees'])->name('comptes.donnees');
        Route::post('/Ajouter-Comptes', [ComptesController::class, 'ajouter'])->name('comptes.ajouter');
        Route::get('/comptes/{id}', [ComptesController::class, 'recuperer'])->name('comptes.recuperer');
        Route::put('/comptes/{id}', [ComptesController::class, 'modifier'])->name('comptes.modifier');
        Route::patch('/comptes/{id}/mot-de-passe', [ComptesController::class, 'reinitialiserMotDePasse'])->name('comptes.mot-de-passe');
        Route::patch('/comptes/{id}/statut', [ComptesController::class, 'basculerStatut'])->name('comptes.statut');
        Route::delete('/comptes/{id}', [ComptesController::class, 'supprimer'])->name('comptes.supprimer');

        Route::get('/compte-restaurant', [ComptesRestauxController::class, 'index'])->name('compterestau.index');
        Route::post('/ajouter-compte-restaurant', [ComptesRestauxController::class, 'ajouter'])->name('compterestau.ajouter');
        Route::get('/compte-restaurant-recuperer/{id}', [ComptesRestauxController::class, 'recuperer'])->name('compterestau.recuperer');
        Route::post('/modifier-compte-restaurant', [ComptesRestauxController::class, 'modifier'])->name('compterestau.modifier');
        Route::delete('/supprimer-compte-restaurant/{id}', [ComptesRestauxController::class, 'supprimer'])->name('compterestau.supprimer');
    });

    Route::middleware('permission:Facturations.Voir les Facturations')->group(function () {
        Route::get('/Facturations', [FacturationController::class, 'index'])->name('facturations.index');
        Route::post('/Facturations-Sacn-Qr-Code', [FacturationController::class, 'scanqrcode'])->name('facturations.scanqrcode');
        Route::get('/Facturations-refresh', [FacturationController::class, 'refresh'])->name('facturations.refresh');
    });

});

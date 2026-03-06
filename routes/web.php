<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmployeController;
use Illuminate\Support\Facades\Route;

$modules = [
    'clients' => [
        'title' => 'Clients',
        'description' => 'Suivi des profils clients, contacts et historique des services.',
    ],
    'fournisseurs' => [
        'title' => 'Fournisseurs',
        'description' => 'Gestion des partenaires, contrats et conditions d achat.',
    ],
    'employes' => [
        'title' => 'Employes',
        'description' => 'Pilotage des equipes, roles et disponibilites atelier.',
    ],
    'voitures' => [
        'title' => 'Voitures',
        'description' => 'Inventaire complet des vehicules, etats et disponibilites.',
    ],
    'facturations' => [
        'title' => 'Facturations',
        'description' => 'Suivi des factures, statuts de paiement et echeances.',
    ],
    'paiements' => [
        'title' => 'Paiements',
        'description' => 'Historique des reglements, moyens de paiement et validations.',
    ],
    'garanties' => [
        'title' => 'Garanties',
        'description' => 'Controle des couvertures, dates d expiration et litiges.',
    ],
    'documents' => [
        'title' => 'Documents',
        'description' => 'Centralisation des pieces administratives et contrats.',
    ],
    'categorie_voiture' => [
        'title' => 'Categorie voiture',
        'description' => 'Classification des vehicules par segment et usage.',
    ],
];

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () use ($modules) {
    Route::resource('employes', EmployeController::class);

    Route::get('/', function () use ($modules) {
        return view('dashboard.index', [
            'modules' => $modules,
        ]);
    })->name('dashboard');

    Route::get('/gestion/{module}', function (string $module) use ($modules) {
        abort_unless(array_key_exists($module, $modules), 404);

        $moduleData = $modules[$module];

        return view('dashboard.module', [
            'currentModule' => $module,
            'moduleTitle' => $moduleData['title'],
            'moduleDescription' => $moduleData['description'],
            'modules' => $modules,
        ]);
    })->name('module.show');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


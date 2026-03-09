<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\ModuleController;
use App\Models\OrigineMarque;
use App\Models\TypeVehicule;
use App\Models\Vente;
use App\Models\Voiture;
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
        $ventesMoisCount = Vente::query()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count();

        $chiffreMois = Vente::query()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('prix_final');

        $voituresDisponiblesCount = Voiture::query()->doesntHave('vente')->count();
        $voituresAvecImageCount = Voiture::query()->whereHas('imagePrincipale')->count();

        $topModeleVendu = Vente::query()
            ->join('voitures', 'voitures.id', '=', 'ventes.id_voiture')
            ->selectRaw('voitures.marque, voitures.model, COUNT(*) as total_ventes')
            ->groupBy('voitures.marque', 'voitures.model')
            ->orderByDesc('total_ventes')
            ->first();

        $originesStats = OrigineMarque::query()
            ->withCount('voitures')
            ->orderByDesc('voitures_count')
            ->limit(8)
            ->get();

        $typesStats = TypeVehicule::query()
            ->withCount('voitures')
            ->orderByDesc('voitures_count')
            ->limit(8)
            ->get();

        $voituresAvecImages = Voiture::query()
            ->with(['imagePrincipale', 'typeVehicule', 'origineMarque'])
            ->whereHas('imagePrincipale')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        $ventesParVendeur = Vente::query()
            ->join('employes', 'employes.id', '=', 'ventes.id_employe')
            ->selectRaw('employes.id, employes.nom, employes.prenom, COUNT(*) as total_ventes, COALESCE(SUM(ventes.prix_final), 0) as total_montant')
            ->groupBy('employes.id', 'employes.nom', 'employes.prenom')
            ->orderByDesc('total_ventes')
            ->limit(10)
            ->get();

        return view('dashboard.index', [
            'modules' => $modules,
            'ventesMoisCount' => $ventesMoisCount,
            'chiffreMois' => $chiffreMois,
            'voituresDisponiblesCount' => $voituresDisponiblesCount,
            'voituresAvecImageCount' => $voituresAvecImageCount,
            'topModeleVendu' => $topModeleVendu,
            'originesStats' => $originesStats,
            'typesStats' => $typesStats,
            'voituresAvecImages' => $voituresAvecImages,
            'ventesParVendeur' => $ventesParVendeur,
        ]);
    })->name('dashboard');

    Route::get('/gestion/{module}', [ModuleController::class, 'show'])->name('module.show');
    Route::post('/gestion/{module}', [ModuleController::class, 'store'])->name('module.store');
    Route::put('/gestion/{module}/{id}', [ModuleController::class, 'update'])->name('module.update');
    Route::delete('/gestion/{module}/{id}', [ModuleController::class, 'destroy'])->name('module.destroy');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

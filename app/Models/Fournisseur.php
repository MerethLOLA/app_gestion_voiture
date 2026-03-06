<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FournisseurController extends Controller
{
    /**
     * Constructeur - Protéger toutes les routes
     */
    public function __construct()
    {
        // Seuls PDG, DG, et DC peuvent accéder
        $this->middleware('permission:view_fournisseurs');
    }

    /**
     * Afficher la liste des fournisseurs
     */
    public function index()
    {
        $user = Auth::user();

        // Vérifier l'accès
        if (!$user->canViewFournisseurs()) {
            abort(403, 'Accès interdit. Seuls le PDG, le Directeur Général et le Directeur Commercial peuvent voir les fournisseurs.');
        }

        $fournisseurs = Fournisseur::all();

        return view('fournisseurs.index', compact('fournisseurs'));
    }

    /**
     * Créer un nouveau fournisseur
     */
    public function store(Request $request)
    {
        // Vérifier la permission de gestion
        if (!Auth::user()->hasPermission('manage_fournisseurs')) {
            abort(403, 'Vous ne pouvez pas gérer les fournisseurs.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'adresse' => 'nullable|string|max:200',
            'contact' => 'nullable|string|max:50',
            'vehicule_fournis' => 'nullable|string|max:100',
        ]);

        $fournisseur = Fournisseur::create($validated);

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur ajouté avec succès.');
    }

    // ... autres méthodes
}

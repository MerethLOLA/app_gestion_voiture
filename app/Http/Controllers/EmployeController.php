<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeController extends Controller
{
    public function index(): View
    {
        $employes = Employe::query()->orderByDesc('id')->paginate(12);

        return view('employes.index', [
            'employes' => $employes,
            'modules' => $this->modules(),
        ]);
    }

    public function create(): View
    {
        return view('employes.create', [
            'modules' => $this->modules(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:50'],
            'prenom' => ['required', 'string', 'max:50'],
            'poste' => ['nullable', 'string', 'max:50'],
            'adresse' => ['nullable', 'string', 'max:200'],
            'email' => ['nullable', 'email', 'max:100'],
            'salaire' => ['nullable', 'numeric', 'min:0'],
            'date_embauche' => ['nullable', 'date'],
            'contrat' => ['required', 'in:stage,cdd,cdi'],
        ]);

        Employe::create($data);

        return redirect()
            ->route('employes.index')
            ->with('status', 'Employe cree avec succes.');
    }

    public function show(Employe $employe): View
    {
        $employe->load('user');

        return view('employes.show', [
            'employe' => $employe,
            'modules' => $this->modules(),
        ]);
    }

    public function edit(Employe $employe): View
    {
        return view('employes.edit', [
            'employe' => $employe,
            'modules' => $this->modules(),
        ]);
    }

    public function update(Request $request, Employe $employe): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:50'],
            'prenom' => ['required', 'string', 'max:50'],
            'poste' => ['nullable', 'string', 'max:50'],
            'adresse' => ['nullable', 'string', 'max:200'],
            'email' => ['nullable', 'email', 'max:100'],
            'salaire' => ['nullable', 'numeric', 'min:0'],
            'date_embauche' => ['nullable', 'date'],
            'contrat' => ['required', 'in:stage,cdd,cdi'],
        ]);

        $employe->update($data);

        return redirect()
            ->route('employes.index')
            ->with('status', 'Employe modifie avec succes.');
    }

    public function destroy(Employe $employe): RedirectResponse
    {
        $employe->delete();

        return redirect()
            ->route('employes.index')
            ->with('status', 'Employe supprime avec succes.');
    }

    private function modules(): array
    {
        return [
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
    }
}

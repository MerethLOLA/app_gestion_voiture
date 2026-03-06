<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FournisseurController extends Controller
{
    public function index(): View
    {
        $fournisseurs = DB::table('fournisseurs')->orderByDesc('id')->get();

        return view('fournisseurs.index', [
            'fournisseurs' => $fournisseurs,
        ]);
    }

    public function show(int $id): View
    {
        $fournisseur = DB::table('fournisseurs')->where('id', $id)->first();

        abort_unless($fournisseur, 404);

        return view('fournisseurs.show', [
            'fournisseur' => $fournisseur,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'adresse' => ['nullable', 'string', 'max:200'],
            'contact' => ['nullable', 'string', 'max:50'],
            'vehicule_fournis' => ['nullable', 'string', 'max:100'],
        ]);

        DB::table('fournisseurs')->insert($data);

        return redirect()->route('fournisseurs.index')
            ->with('status', 'Fournisseur ajoute avec succes.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'adresse' => ['nullable', 'string', 'max:200'],
            'contact' => ['nullable', 'string', 'max:50'],
            'vehicule_fournis' => ['nullable', 'string', 'max:100'],
        ]);

        DB::table('fournisseurs')->where('id', $id)->update($data);

        return redirect()->route('fournisseurs.index')
            ->with('status', 'Fournisseur mis a jour avec succes.');
    }

    public function destroy(int $id): RedirectResponse
    {
        DB::table('fournisseurs')->where('id', $id)->delete();

        return redirect()->route('fournisseurs.index')
            ->with('status', 'Fournisseur supprime avec succes.');
    }
}

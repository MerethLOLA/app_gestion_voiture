@extends('layouts.dashboard')

@section('title', 'Tableau de bord - Gestion voiture')
@section('page_title', 'Tableau de bord')

@section('content')
    <section class="cards-grid">
        <article class="kpi-card">
            <p>Ventes du mois</p>
            <h2>{{ $ventesMoisCount }}</h2>
            <span>{{ now()->translatedFormat('F Y') }}</span>
        </article>
        <article class="kpi-card">
            <p>Chiffre du mois</p>
            <h2>{{ number_format((float) $chiffreMois, 0, ',', ' ') }}</h2>
            <span>MAD cumules</span>
        </article>
        <article class="kpi-card">
            <p>Voitures disponibles</p>
            <h2>{{ $voituresDisponiblesCount }}</h2>
            <span>Hors vehicules deja vendus</span>
        </article>
        <article class="kpi-card">
            <p>Modele le plus vendu</p>
            <h2>{{ $topModeleVendu ? $topModeleVendu->marque . ' ' . $topModeleVendu->model : 'N/A' }}</h2>
            <span>{{ $topModeleVendu ? $topModeleVendu->total_ventes . ' vente(s)' : 'Aucune vente enregistree' }}</span>
        </article>
    </section>

    <section class="panel">
        <div class="panel-head">
            <div>
                <h3>Modules de gestion</h3>
                <p>Acces rapide a toutes les tables metier</p>
            </div>
            <button type="button" class="btn-secondary">Voir le planning</button>
        </div>
        <div class="module-grid">
            @foreach($modules as $slug => $module)
                @php
                    $user = auth()->user();
                    $modulePermissions = [
                        'clients' => ['manage_clients'],
                        'fournisseurs' => ['view_fournisseurs', 'manage_fournisseurs'],
                        'employes' => ['view_employes', 'manage_employes'],
                        'voitures' => ['manage_voitures'],
                        'facturations' => ['manage_factures'],
                        'paiements' => ['manage_paiements'],
                        'garanties' => ['manage_voitures'],
                        'documents' => ['manage_voitures'],
                        'categorie_voiture' => ['manage_voitures'],
                    ];
                    $canAccessModule = collect($modulePermissions[$slug] ?? [])
                        ->contains(fn ($perm) => $user && $user->hasPermission($perm));
                @endphp

                @if($canAccessModule)
                    @php($moduleHref = $slug === 'employes' ? route('employes.index') : route('module.show', $slug))
                    <a href="{{ $moduleHref }}" class="module-card">
                        <h4>{{ $module['title'] }}</h4>
                        <p>{{ $module['description'] }}</p>
                    </a>
                @else
                    <article class="module-card">
                        <h4>{{ $module['title'] }}</h4>
                        <p>Vous n'avez pas acces a ce module avec votre role.</p>
                    </article>
                @endif
            @endforeach
        </div>
    </section>

    <section class="panel">
        <div class="panel-head">
            <div>
                <h3>Origines et types vehicules</h3>
                <p>Repartition du stock par origine de marque et type</p>
            </div>
            <div class="feedback-row">
                <p class="feedback success">{{ $voituresAvecImageCount }} voiture(s) avec image principale</p>
            </div>
        </div>

        <div class="state-grid">
            <article class="state-card">
                <h4>Origine marque</h4>
                @if($originesStats->isEmpty())
                    <p>Aucune donnee disponible.</p>
                @else
                    @foreach($originesStats as $origine)
                        <p><strong>{{ $origine->nom }}</strong> : {{ $origine->voitures_count }} voiture(s)</p>
                    @endforeach
                @endif
            </article>
            <article class="state-card">
                <h4>Type vehicule</h4>
                @if($typesStats->isEmpty())
                    <p>Aucune donnee disponible.</p>
                @else
                    @foreach($typesStats as $type)
                        <p><strong>{{ $type->nom }}</strong> : {{ $type->voitures_count }} voiture(s)</p>
                    @endforeach
                @endif
            </article>
        </div>
    </section>

    <section class="panel">
        <div class="panel-head">
            <div>
                <h3>Voitures avec images</h3>
                <p>Derniers vehicules disposant d une image principale</p>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Voiture</th>
                        <th>Origine</th>
                        <th>Type</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($voituresAvecImages as $voiture)
                        <tr>
                            <td>#{{ $voiture->id }}</td>
                            <td>{{ $voiture->marque }} {{ $voiture->model }}</td>
                            <td>{{ $voiture->origineMarque->nom ?? '-' }}</td>
                            <td>{{ $voiture->typeVehicule->nom ?? '-' }}</td>
                            <td>
                                <a href="{{ $voiture->image_principale_url }}" target="_blank" rel="noopener" class="btn-link">Voir image</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Aucune voiture avec image principale.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <div class="panel-head">
            <div>
                <h3>Ventes par vendeur</h3>
                <p>Classement des vendeurs par nombre de ventes</p>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Vendeur</th>
                        <th>Total ventes</th>
                        <th>Montant cumule (MAD)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventesParVendeur as $vendeur)
                        <tr>
                            <td>{{ $vendeur->prenom }} {{ $vendeur->nom }}</td>
                            <td>{{ $vendeur->total_ventes }}</td>
                            <td>{{ number_format((float) $vendeur->total_montant, 0, ',', ' ') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Aucune vente enregistree par vendeur.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

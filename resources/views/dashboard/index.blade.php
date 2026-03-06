@extends('layouts.dashboard')

@section('title', 'Tableau de bord - Gestion voiture')
@section('page_title', 'Tableau de bord')

@section('content')
    <section class="cards-grid">
        <article class="kpi-card">
            <p>Ventes du mois</p>
            <h2>64</h2>
            <span>+14% vs janvier</span>
        </article>
        <article class="kpi-card">
            <p>Delai moyen de livraison</p>
            <h2>3.8 j</h2>
            <span>-0.7 jour sur 30 jours</span>
        </article>
        <article class="kpi-card">
            <p>Taux de conversion devis</p>
            <h2>42%</h2>
            <span>Objectif trimestriel: 45%</span>
        </article>
        <article class="kpi-card">
            <p>Modele le plus vendu</p>
            <h2>Dacia Sandero</h2>
            <span>18 ventes confirmees</span>
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
                @php($moduleHref = $slug === 'employes' ? route('employes.index') : route('module.show', $slug))
                <a href="{{ $moduleHref }}" class="module-card">
                    <h4>{{ $module['title'] }}</h4>
                    <p>{{ $module['description'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <section class="panel">
        <div class="panel-head">
            <div>
                <h3>Activites recentes</h3>
                <p>Vision rapide des operations</p>
            </div>
            <div class="feedback-row">
                <p class="feedback success">Mise a jour synchronisee</p>
            </div>
        </div>

        <form class="filters-bar" action="#" method="GET">
            <input type="search" class="input-search" placeholder="Rechercher une operation, reference ou utilisateur">
            <select class="input-select">
                <option>Tous les statuts</option>
                <option>Valide</option>
                <option>En controle</option>
                <option>En attente</option>
            </select>
            <input type="date" class="input-date">
            <button type="submit" class="btn-primary">Appliquer</button>
            <button type="button" class="btn-secondary">Reinitialiser</button>
        </form>

        <div class="table-meta">
            <p>24 operations sur les 7 derniers jours</p>
            <button type="button" class="btn-link">Actualiser</button>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Evenement <span class="sort-arrow">^</span></th>
                        <th>Reference</th>
                        <th>Utilisateur</th>
                        <th>Date <span class="sort-arrow">v</span></th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Creation facture</td>
                        <td>FAC-2026-0142</td>
                        <td>Amine B.</td>
                        <td>13/02/2026</td>
                        <td><span class="badge ok">Valide</span></td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn-icon">Voir</button>
                                <button type="button" class="btn-icon">Editer</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Ajout voiture</td>
                        <td>VH-2981</td>
                        <td>Salma E.</td>
                        <td>13/02/2026</td>
                        <td><span class="badge info">En controle</span></td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn-icon">Voir</button>
                                <button type="button" class="btn-icon">Editer</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Paiement recu</td>
                        <td>PAY-9826</td>
                        <td>Finance</td>
                        <td>12/02/2026</td>
                        <td><span class="badge ok">Recu</span></td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn-icon">Voir</button>
                                <button type="button" class="btn-icon">Editer</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pagination-bar">
            <p>Page 1 sur 4</p>
            <div class="pagination-actions">
                <button type="button" class="btn-secondary">Precedent</button>
                <button type="button" class="btn-secondary">1</button>
                <button type="button" class="btn-primary">2</button>
                <button type="button" class="btn-secondary">3</button>
                <button type="button" class="btn-secondary">Suivant</button>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.dashboard')

@section('title', $moduleTitle . ' - Gestion voiture')
@section('page_title', $moduleTitle)

@section('content')
    <section class="panel">
        <div class="panel-head">
            <div>
                <h3>{{ $moduleTitle }}</h3>
                <p>{{ $moduleDescription }}</p>
            </div>
            <div class="feedback-row">
                <p class="feedback info">Vue filtree par defaut: 30 derniers jours</p>
            </div>
        </div>

        <form class="filters-bar" action="#" method="GET">
            <input type="search" class="input-search" placeholder="Rechercher dans {{ strtolower($moduleTitle) }}">
            <select class="input-select">
                <option>Tous les statuts</option>
                <option>Actif</option>
                <option>En revision</option>
                <option>En attente</option>
            </select>
            <input type="date" class="input-date">
            <button type="submit" class="btn-primary">Appliquer</button>
            <button type="button" class="btn-secondary">Reinitialiser</button>
        </form>

        <div class="actions-row">
            <button class="btn-primary">Nouveau</button>
            <button class="btn-secondary">Exporter</button>
            <button class="btn-secondary">Filtrer</button>
            <button type="button" class="btn-secondary" data-density-toggle>Mode compact</button>
        </div>

        <div class="table-meta">
            <p>Affichage de 1 a 3 sur 18 resultats</p>
            <button type="button" class="btn-link">Actualiser</button>
        </div>

        <div class="table-wrap">
            <table data-density-table>
                <thead>
                    <tr>
                        <th>ID <span class="sort-arrow">^</span></th>
                        <th>Nom</th>
                        <th>Reference</th>
                        <th>Date creation <span class="sort-arrow">v</span></th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#001</td>
                        <td>{{ $moduleTitle }} Alpha</td>
                        <td>{{ strtoupper(substr($currentModule, 0, 3)) }}-2026-01</td>
                        <td>11/02/2026</td>
                        <td><span class="badge ok">Actif</span></td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn-icon">Voir</button>
                                <button type="button" class="btn-icon">Editer</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#002</td>
                        <td>{{ $moduleTitle }} Beta</td>
                        <td>{{ strtoupper(substr($currentModule, 0, 3)) }}-2026-02</td>
                        <td>12/02/2026</td>
                        <td><span class="badge info">En revision</span></td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn-icon">Voir</button>
                                <button type="button" class="btn-icon">Editer</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#003</td>
                        <td>{{ $moduleTitle }} Gamma</td>
                        <td>{{ strtoupper(substr($currentModule, 0, 3)) }}-2026-03</td>
                        <td>13/02/2026</td>
                        <td><span class="badge alert">En attente</span></td>
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
            <p>Page 1 sur 6</p>
            <div class="pagination-actions">
                <button type="button" class="btn-secondary">Precedent</button>
                <button type="button" class="btn-primary">1</button>
                <button type="button" class="btn-secondary">2</button>
                <button type="button" class="btn-secondary">3</button>
                <button type="button" class="btn-secondary">Suivant</button>
            </div>
        </div>
    </section>

    <section class="state-grid">
        <article class="state-card">
            <h4>Etat vide</h4>
            <p>Aucune donnee ne correspond aux filtres selectionnes.</p>
            <button type="button" class="btn-secondary">Effacer les filtres</button>
        </article>
        <article class="state-card">
            <h4>Chargement</h4>
            <div class="loading-lines">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </article>
        <article class="state-card">
            <h4>Feedback</h4>
            <p class="feedback success">Enregistrement cree avec succes.</p>
            <p class="feedback error">Une reference existe deja.</p>
        </article>
    </section>
@endsection

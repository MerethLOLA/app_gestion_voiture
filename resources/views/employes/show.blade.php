@extends('layouts.dashboard')

@section('title', 'Detail employe - Gestion voiture')
@section('page_title', 'Detail employe')

@section('content')
    <section class="panel">
        <div class="panel-head">
            <div>
                <h3>{{ $employe->nom }} {{ $employe->prenom }}</h3>
                <p>Fiche detaillee de l employe.</p>
            </div>
            <div class="actions-row">
                <a href="{{ route('employes.edit', $employe) }}" class="btn-primary">Modifier</a>
                <a href="{{ route('employes.index') }}" class="btn-secondary">Retour liste</a>
            </div>
        </div>

        <div class="detail-grid">
            <article class="state-card">
                <h4>Informations</h4>
                <p><strong>Poste:</strong> {{ $employe->poste ?? '-' }}</p>
                <p><strong>Email:</strong> {{ $employe->email ?? '-' }}</p>
                <p><strong>Adresse:</strong> {{ $employe->adresse ?? '-' }}</p>
            </article>

            <article class="state-card">
                <h4>Contrat</h4>
                <p><strong>Type:</strong> {{ strtoupper($employe->contrat) }}</p>
                <p><strong>Date embauche:</strong> {{ $employe->date_embauche ?? '-' }}</p>
                <p><strong>Salaire:</strong> {{ $employe->salaire ? number_format((float) $employe->salaire, 2, ',', ' ') . ' MAD' : '-' }}</p>
            </article>

            <article class="state-card">
                <h4>Compte utilisateur</h4>
                @if($employe->user)
                    <p><strong>Username:</strong> {{ $employe->user->username }}</p>
                    <p><strong>Email:</strong> {{ $employe->user->email }}</p>
                    <p><strong>Role:</strong> {{ $employe->user->role }}</p>
                @else
                    <p>Aucun compte utilisateur lie.</p>
                @endif
            </article>
        </div>
    </section>
@endsection

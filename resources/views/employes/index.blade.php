@extends('layouts.dashboard')

@section('title', 'Employes - Gestion voiture')
@section('page_title', 'Employes')

@section('content')
    <section class="panel">
        <div class="panel-head">
            <div>
                <h3>Liste des employes</h3>
                <p>Gestion de l equipe commerciale et administrative.</p>
            </div>
            <a href="{{ route('employes.create') }}" class="btn-primary">Nouveau employe</a>
        </div>

        @if(session('status'))
            <p class="feedback success">{{ session('status') }}</p>
        @endif

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom complet</th>
                        <th>Poste</th>
                        <th>Email</th>
                        <th>Contrat</th>
                        <th>Salaire</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employes as $employe)
                        <tr>
                            <td>#{{ $employe->id }}</td>
                            <td>{{ $employe->nom }} {{ $employe->prenom }}</td>
                            <td>{{ $employe->poste ?? '-' }}</td>
                            <td>{{ $employe->email ?? '-' }}</td>
                            <td><span class="badge info">{{ strtoupper($employe->contrat) }}</span></td>
                            <td>{{ $employe->salaire ? number_format((float) $employe->salaire, 2, ',', ' ') . ' MAD' : '-' }}</td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('employes.show', $employe) }}" class="btn-icon">Voir</a>
                                    <a href="{{ route('employes.edit', $employe) }}" class="btn-icon">Editer</a>
                                    <form method="POST" action="{{ route('employes.destroy', $employe) }}" onsubmit="return confirm('Supprimer cet employe ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon danger">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Aucun employe trouve.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="laravel-pagination">
            {{ $employes->links() }}
        </div>
    </section>
@endsection

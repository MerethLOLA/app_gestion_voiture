@extends('layouts.dashboard')

@section('title', 'Modifier employe - Gestion voiture')
@section('page_title', 'Modifier employe')

@section('content')
    <section class="panel">
        <div class="panel-head">
            <div>
                <h3>Modifier {{ $employe->nom }} {{ $employe->prenom }}</h3>
                <p>Mettez a jour les informations du collaborateur.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('employes.update', $employe) }}" class="crud-form">
            @csrf
            @method('PUT')
            @include('modules.employes._form', ['submitLabel' => 'Enregistrer'])
        </form>
    </section>
@endsection

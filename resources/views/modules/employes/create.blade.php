@extends('layouts.dashboard')

@section('title', 'Nouveau employe - Gestion voiture')
@section('page_title', 'Nouveau employe')

@section('content')
    <section class="panel">
        <div class="panel-head">
            <div>
                <h3>Creer un employe</h3>
                <p>Renseignez les informations principales.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('employes.store') }}" class="crud-form">
            @csrf
            @include('modules.employes._form', ['submitLabel' => 'Creer'])
        </form>
    </section>
@endsection

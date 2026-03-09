@extends('layouts.dashboard')

@section('title', $moduleTitle . ' - Gestion voiture')
@section('page_title', $moduleTitle)

@section('content')
    @include('modules._crud')
@endsection

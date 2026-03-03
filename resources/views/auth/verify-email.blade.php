@extends('layouts.auth')

@section('title', 'Vérification Email')
@section('subtitle', 'Confirmez votre adresse email')

@section('content')

<p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
    Merci de vérifier votre email avant de continuer.
</p>

<form method="POST" action="{{ route('verification.send') }}">
    @csrf

    <button class="btn-auth w-full">
        Renvoyer l'email de vérification
    </button>
</form>

@endsection
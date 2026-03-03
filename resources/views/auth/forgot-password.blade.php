@extends('layouts.auth')

@section('title', 'Mot de passe oublié')
@section('subtitle', 'Réinitialisez votre mot de passe')

@section('content')

<form method="POST" action="{{ route('password.email') }}" class="space-y-6">
    @csrf

    <input type="email" name="email" required
           placeholder="Votre email"
           class="input-auth">

    <button type="submit" class="btn-auth">
        Envoyer le lien
    </button>
</form>

@endsection
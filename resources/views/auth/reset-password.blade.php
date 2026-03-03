@extends('layouts.auth')

@section('title', 'Réinitialisation')
@section('subtitle', 'Choisissez un nouveau mot de passe')

@section('content')

<form method="POST" action="{{ route('password.update') }}" class="space-y-6">
    @csrf

    <input type="hidden" name="token" value="{{ request()->route('token') }}">

    <input type="email" name="email" required class="input-auth">
    <input type="password" name="password" required class="input-auth">
    <input type="password" name="password_confirmation" required class="input-auth">

    <button type="submit" class="btn-auth">
        Réinitialiser
    </button>
</form>

@endsection
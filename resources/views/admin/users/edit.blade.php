@extends('layouts.admin')

@section('title', 'Modifier l\'utilisateur')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Retour et Titre --}}
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 flex items-center gap-2 text-sm font-bold hover:underline mb-2 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Retour à la liste
        </a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Modifier : {{ $user->name }}</h1>
        <p class="text-sm text-gray-500">Mettez à jour les informations du compte ou changez le mot de passe.</p>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            {{-- Informations de base --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Adresse Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>
            </div>

            {{-- Sécurité : Changement de mot de passe --}}
            <div class="p-6 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-800">
                <h3 class="text-sm font-bold text-blue-800 dark:text-blue-300 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Sécurité (Optionnel)
                </h3>
                <p class="text-xs text-blue-600 dark:text-blue-400 mb-4">Laissez vide si vous ne souhaitez pas changer le mot de passe.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Nouveau mot de passe</label>
                        <input type="password" name="password" 
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Confirmer</label>
                        <input type="password" name="password_confirmation" 
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                </div>
            </div>

            {{-- Gestion du Rôle --}}
            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_admin" value="1" id="is_admin" 
                        {{ $user->is_admin ? 'checked' : '' }}
                        {{ auth()->id() === $user->id ? 'disabled' : '' }}
                        class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                    <div>
                        <label for="is_admin" class="text-sm font-bold text-gray-700 dark:text-gray-300">Droits Administrateur</label>
                        <p class="text-[11px] text-gray-500 italic">Accès total au panneau de contrôle.</p>
                    </div>
                </div>
                
                @if(auth()->id() === $user->id)
                    <input type="hidden" name="is_admin" value="1">
                    <span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-1 rounded font-bold uppercase">Protection de session</span>
                @endif
            </div>

            {{-- Boutons d'action --}}
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-100 dark:shadow-none transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Mettre à jour le profil
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-4 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-all">
                    Annuler
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
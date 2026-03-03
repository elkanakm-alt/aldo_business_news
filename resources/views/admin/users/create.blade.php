@extends('layouts.admin')

@section('title', 'Nouveau Utilisateur')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Fil d'ariane / Retour --}}
    <div class="mb-8">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 transition-colors group">
            <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
            Retour à la liste
        </a>
        <h1 class="text-3xl font-black text-gray-800 dark:text-white mt-3">Créer un compte</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Remplissez les informations pour ajouter un nouveau membre à l'équipe.</p>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">
        @csrf
        
        <div class="space-y-6">
            {{-- Nom complet --}}
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2 ml-1">Nom complet</label>
                <div class="relative">
                    <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ex: Jean Dupont"
                        class="w-full pl-11 pr-4 py-3.5 rounded-2xl border @error('name') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror dark:bg-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all dark:text-white">
                </div>
                @error('name') <p class="text-red-500 text-xs mt-2 font-bold italic">{{ $message }}</p> @enderror
            </div>

            {{-- Adresse Email --}}
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2 ml-1">Adresse Email</label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="jean@exemple.com"
                        class="w-full pl-11 pr-4 py-3.5 rounded-2xl border @error('email') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror dark:bg-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all dark:text-white">
                </div>
                @error('email') <p class="text-red-500 text-xs mt-2 font-bold italic">{{ $message }}</p> @enderror
            </div>

            {{-- Mots de passe --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2 ml-1">Mot de passe</label>
                    <div class="relative" x-data="{ show: false }">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input :type="show ? 'text' : 'password'" name="password" required
                            class="w-full pl-11 pr-12 py-3.5 rounded-2xl border @error('password') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror dark:bg-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all dark:text-white">
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-500">
                            <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-2 font-bold italic">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2 ml-1">Confirmation</label>
                    <div class="relative">
                        <i class="fa-solid fa-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="password" name="password_confirmation" required
                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-gray-200 dark:border-gray-600 dark:bg-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all dark:text-white">
                    </div>
                </div>
            </div>

            {{-- Role --}}
            <div class="p-5 bg-blue-50/50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-900/30">
                <div class="flex items-center gap-4">
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_admin" value="1" id="is_admin" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <label for="is_admin" class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-300">Accès Administrateur</label>
                    </div>
                </div>
                <p class="text-[11px] text-blue-600/70 dark:text-blue-400/70 mt-2 ml-14 italic">L'administrateur peut modifier les articles et gérer les autres utilisateurs.</p>
            </div>

            {{-- Bouton Submit --}}
            <button type="submit" class="group relative w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-blue-500/20 transition-all flex items-center justify-center gap-3 overflow-hidden">
                <span class="relative z-10 text-sm uppercase tracking-widest">Enregistrer l'utilisateur</span>
                <i class="fa-solid fa-paper-plane relative z-10 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
            </button>
        </div>
    </form>
</div>
@endsection
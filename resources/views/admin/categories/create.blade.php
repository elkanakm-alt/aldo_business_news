@extends('layouts.admin')

@section('title', 'Nouvelle Catégorie')

@section('content')
<div class="py-6 max-w-4xl mx-auto">
    {{-- Fil d'ariane --}}
    <div class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-gray-400 mb-4">
        <a href="{{ route('admin.categories.index') }}" class="hover:text-blue-600 transition-colors">Catégories</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 dark:text-white">Création</span>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] shadow-xl shadow-blue-500/5 border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="p-8 md:p-12">
            <h1 class="text-3xl font-black text-gray-800 dark:text-white tracking-tighter uppercase mb-8">Nouvelle Catégorie</h1>

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    {{-- Nom de la catégorie --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2 ml-1">Nom de la thématique</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all placeholder:text-gray-300"
                            placeholder="Ex: Économie, Technologie...">
                        @error('name') <p class="text-rose-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Couleur ou Icône (Optionnel - Pour le design) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2 ml-1">Couleur d'accentuation</label>
                            <input type="color" name="color" value="#3b82f6" 
                                class="w-full h-14 p-1 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2 ml-1">Slug (Auto-généré)</label>
                            <input type="text" disabled placeholder="Sera généré automatiquement"
                                class="w-full px-6 py-4 bg-gray-100 dark:bg-gray-800/50 border-none rounded-2xl text-sm font-medium text-gray-400 cursor-not-allowed">
                        </div>
                    </div>

                    <div class="pt-6 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-1 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-lg shadow-blue-500/20 transition-all">
                            Enregistrer la catégorie
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="px-8 py-4 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-gray-200 transition-all text-center">
                            Annuler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
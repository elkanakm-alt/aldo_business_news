@extends('layouts.admin')

@section('title', 'Modifier la Catégorie')

@section('content')
<div class="py-6 max-w-4xl mx-auto">
    {{-- Fil d'ariane --}}
    <div class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-gray-400 mb-4">
        <a href="{{ route('admin.categories.index') }}" class="hover:text-blue-600 transition-colors">Catégories</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 dark:text-white">Édition</span>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] shadow-xl shadow-amber-500/5 border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="p-8 md:p-12">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-black text-gray-800 dark:text-white tracking-tighter uppercase">Modifier : {{ $category->name }}</h1>
                <span class="px-4 py-1.5 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[10px] font-black rounded-full uppercase border border-amber-100 dark:border-amber-800">Édition en cours</span>
            </div>

            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    {{-- Nom --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2 ml-1">Nom de la catégorie</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                            class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-amber-500 transition-all">
                        @error('name') <p class="text-rose-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Stats rapides --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-6 bg-blue-50 dark:bg-blue-900/20 rounded-3xl border border-blue-100 dark:border-blue-800/50">
                            <p class="text-[10px] font-black uppercase text-blue-400 mb-1">Articles liés</p>
                            <p class="text-2xl font-black text-blue-600 dark:text-blue-400">{{ $category->posts_count ?? $category->posts()->count() }}</p>
                        </div>
                        <div class="p-6 bg-slate-50 dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700">
                            <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Dernière mise à jour</p>
                            <p class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $category->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    <div class="pt-6 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-1 px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-lg shadow-amber-500/20 transition-all">
                            Mettre à jour les informations
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
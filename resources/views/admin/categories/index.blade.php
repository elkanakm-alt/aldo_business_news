@extends('layouts.admin')

@section('title', 'Gestion des Catégories')

@section('content')
<div class="space-y-8">
    {{-- EN-TÊTE ULTRA-DESIGN --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight italic uppercase">
                Catégories <span class="text-blue-600">.</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">
                @if(request('search'))
                    Résultats pour <span class="text-blue-600 font-bold">"{{ request('search') }}"</span>
                @else
                    Structurez votre contenu et optimisez votre SEO.
                @endif
            </p>
        </div>
        
        <a href="{{ route('admin.categories.create') }}" 
           class="group relative inline-flex items-center justify-center px-8 py-4 font-black text-white transition-all duration-200 bg-blue-600 rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-500/25 active:scale-95 text-sm">
            <svg class="w-5 h-5 mr-2 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            NOUVELLE CATÉGORIE
        </a>
    </div>

    {{-- BARRE DE RECHERCHE & FILTRES --}}
    <div class="bg-white dark:bg-slate-900 p-4 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Rechercher une catégorie (nom ou slug)..." 
                    class="block w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl text-slate-700 dark:text-white placeholder-slate-400 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold">
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 md:flex-none px-8 py-4 bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 text-white font-black rounded-2xl transition-all uppercase text-xs tracking-widest">
                    Rechercher
                </button>

                @if(request('search'))
                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex-1 md:flex-none px-8 py-4 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 font-black rounded-2xl transition-all uppercase text-xs tracking-widest flex items-center justify-center gap-2 border border-rose-100 dark:border-rose-800/50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                        Voir tout
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- STATS RAPIDES --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm transition-transform hover:scale-[1.02]">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Total</span>
                    <span class="text-2xl font-black text-slate-800 dark:text-white">{{ $categories->total() }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLEAU STYLE "HUB" --}}
    <div class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                        <th class="px-8 py-6 text-[11px] font-black uppercase text-slate-400 tracking-[0.2em]">Détails Catégorie</th>
                        <th class="px-8 py-6 text-[11px] font-black uppercase text-slate-400 tracking-[0.2em] text-center">Articles Liés</th>
                        <th class="px-8 py-6 text-[11px] font-black uppercase text-slate-400 tracking-[0.2em] text-center">Date</th>
                        <th class="px-8 py-6 text-[11px] font-black uppercase text-slate-400 tracking-[0.2em] text-right">Gestion</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @forelse($categories as $category)
                    <tr class="group hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all duration-300">
                        {{-- Identité --}}
                        <td class="px-8 py-7">
                            <div class="flex items-center gap-5">
                                <div class="relative">
                                    <div class="h-14 w-14 rounded-[1.25rem] bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-blue-500/30 group-hover:rotate-6 transition-transform">
                                        {{ substr($category->name, 0, 1) }}
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 h-5 w-5 bg-emerald-500 border-4 border-white dark:border-slate-900 rounded-full"></div>
                                </div>
                                <div>
                                    <h4 class="font-black text-base text-slate-800 dark:text-white leading-tight">
                                        {{ $category->name }}
                                    </h4>
                                    <span class="text-[11px] font-bold text-blue-500 uppercase tracking-tighter mt-1 block">
                                        /{{ $category->slug }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- Stats Articles --}}
                        <td class="px-8 py-7 text-center">
                            <div class="inline-flex items-center px-4 py-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-black text-sm border border-transparent group-hover:border-blue-200 transition-colors">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" /></svg>
                                {{ $category->posts_count ?? 0 }}
                            </div>
                        </td>

                        {{-- Date --}}
                        <td class="px-8 py-7 text-center">
                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">
                                {{ $category->created_at->translatedFormat('M Y') }}
                            </span>
                        </td>

                        {{-- Actions Gouttelettes --}}
                        <td class="px-8 py-7 text-right">
                            <div class="flex justify-end items-center gap-3">
                                <a href="{{ route('admin.categories.edit', $category) }}" 
                                   class="p-3 bg-white dark:bg-slate-800 text-slate-400 hover:text-blue-600 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 hover:border-blue-200 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </a>

                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Attention ! Supprimer cette catégorie pourrait déréférencer ses articles.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-3 bg-white dark:bg-slate-800 text-slate-400 hover:text-rose-600 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 hover:border-rose-200 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="max-w-xs mx-auto text-center">
                                <div class="h-24 w-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </div>
                                <p class="text-slate-800 dark:text-white font-black text-xl mb-2 italic">Aucun résultat trouvé</p>
                                <p class="text-slate-400 font-medium mb-6">Nous n'avons trouvé aucune catégorie correspondant à votre recherche.</p>
                                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-blue-600 font-black uppercase text-xs tracking-widest hover:underline">
                                    Réinitialiser les filtres →
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION CUSTOM --}}
        @if($categories->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 dark:bg-slate-800/20 border-t border-slate-100 dark:border-slate-800">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
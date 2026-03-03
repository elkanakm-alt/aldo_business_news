@extends('layouts.admin')

@section('title', 'Gestion des Articles')

@section('content')
<div class="py-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 dark:text-white tracking-tighter uppercase">Articles</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total : {{ $posts->total() }} publications</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-xl shadow-blue-500/20 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nouvel Article
        </a>
    </div>

    {{-- BARRE DE RECHERCHE ET FILTRES --}}
    <div class="bg-white dark:bg-gray-900 p-4 rounded-3xl border border-gray-100 dark:border-gray-800 mb-6 shadow-sm">
        <form action="{{ route('admin.posts.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 flex gap-2">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un titre..." 
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <button type="submit" class="px-4 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-2xl hover:bg-blue-500 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>

            <div class="w-full md:w-64">
                <select name="category" onchange="this.form.submit()" 
                    class="w-full py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-xs font-black uppercase tracking-widest focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    {{-- Tableau --}}
    <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">ID</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Article</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Statut</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Catégorie</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Auteur</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/40 transition-colors group">
                        <td class="px-6 py-4 text-xs font-black text-blue-600/50 text-center">{{ $post->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <img src="{{ $post->image_url }}" class="w-12 h-12 rounded-xl object-cover shadow-sm bg-gray-100">
                                <div class="max-w-[200px]">
                                    <p class="font-bold text-gray-800 dark:text-gray-100 text-sm truncate">{{ $post->title }}</p>
                                    <div class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase">
                                        <span>{{ $post->views }} vues</span>
                                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                        <span>{{ $post->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($post->status === 'published')
                                <span class="px-2.5 py-1 text-[8px] font-black uppercase bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg border border-emerald-100 dark:border-emerald-800">Publié</span>
                            @else
                                <span class="px-2.5 py-1 text-[8px] font-black uppercase bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg border border-amber-100 dark:border-amber-800">Brouillon</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-[9px] font-black uppercase bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg">
                                {{ $post->category->name ?? 'Sans catégorie' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-[8px] font-bold">
                                    {{ substr($post->user->name ?? 'A', 0, 1) }}
                                </div>
                                <span class="text-[11px] font-bold text-gray-600 dark:text-gray-300">{{ $post->user->name ?? 'Admin' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                {{-- Voir (On vérifie si la route existe pour éviter l'erreur 500) --}}
                                @if(Route::has('posts.show'))
                                    <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="p-2 text-gray-400 hover:text-emerald-500 bg-gray-100 dark:bg-gray-800 rounded-xl transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                @endif

                                <a href="{{ route('admin.posts.edit', $post) }}" class="p-2 text-gray-400 hover:text-blue-600 bg-gray-100 dark:bg-gray-800 rounded-xl transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cet article ?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-gray-400 hover:text-rose-600 bg-gray-100 dark:bg-gray-800 rounded-xl transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 4v4h4"></path></svg>
                                </div>
                                <p class="text-gray-400 font-bold italic">Aucun article trouvé.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
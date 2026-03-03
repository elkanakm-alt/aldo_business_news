@extends('layouts.admin')

@section('title', 'Modération des Commentaires')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- Header & Barre de Recherche --}}
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Modération</h1>
            <p class="text-slate-400 text-sm font-medium mt-1">Gérez les interactions de votre communauté.</p>
        </div>

        <div class="flex flex-col md:flex-row items-center gap-4">
            {{-- Filtres Rapides --}}
            <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl">
                <a href="{{ route('admin.comments.index') }}" 
                   class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ !request('filter') ? 'bg-white dark:bg-slate-700 shadow-sm text-blue-600' : 'text-slate-500' }}">
                    Tous
                </a>
                <a href="{{ route('admin.comments.index', ['filter' => 'pending']) }}" 
                   class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ request('filter') == 'pending' ? 'bg-white dark:bg-slate-700 shadow-sm text-blue-600' : 'text-slate-500' }}">
                    En attente ({{ $totalPending }})
                </a>
            </div>

            {{-- Barre de Recherche --}}
            <form action="{{ route('admin.comments.index') }}" method="GET" class="relative group">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher un auteur ou texte..." 
                       class="w-full md:w-64 pl-10 pr-4 py-2.5 rounded-2xl border-none bg-white dark:bg-slate-900 shadow-sm focus:ring-2 focus:ring-blue-500 transition-all text-sm dark:text-slate-200">
                <div class="absolute left-3 top-3 text-slate-400 group-focus-within:text-blue-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </form>
        </div>
    </div>

    {{-- Table de modération --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-4">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            <th class="px-4 pb-2">Auteur</th>
                            <th class="px-4 pb-2">Commentaire & Réponse</th>
                            <th class="px-4 pb-2">Article</th>
                            <th class="px-4 pb-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($comments as $comment)
                        <tr class="group bg-slate-50/50 dark:bg-slate-800/30 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                            {{-- Auteur --}}
                            <td class="px-4 py-6 rounded-l-3xl">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-black text-xs shadow-lg shadow-blue-500/20">
                                        {{ strtoupper(substr($comment->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-700 dark:text-slate-200">{{ $comment->name }}</p>
                                        <p class="text-[10px] text-slate-400 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $comment->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Texte & Réponse --}}
                            <td class="px-4 py-6 max-w-xs md:max-w-sm">
                                <div class="relative pl-4 border-l-2 border-blue-100 dark:border-slate-700 mb-4">
                                    <p class="text-slate-600 dark:text-slate-400 italic text-xs leading-relaxed">"{{ $comment->content }}"</p>
                                </div>
                                
                                <form action="{{ route('admin.comments.reply', $comment) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="text" name="reply_content" placeholder="Répondre en tant qu'admin..." 
                                           class="flex-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-[11px] outline-none focus:ring-2 focus:ring-blue-500 dark:text-slate-200 transition-all">
                                    <button type="submit" class="bg-blue-600 text-white p-2.5 rounded-xl hover:bg-blue-700 transition-all active:scale-95 shadow-md shadow-blue-500/10">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9-2-9-18-9 18 9 2zm0 0v-8"></path></svg>
                                    </button>
                                </form>
                            </td>

                            {{-- Article --}}
                            <td class="px-4 py-6">
                                <div class="flex flex-col gap-1">
                                    <span class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider text-center border border-blue-100/50 dark:border-blue-900/30">
                                        {{ Str::limit($comment->post->title ?? 'Article supprimé', 20) }}
                                    </span>
                                    @if(!$comment->approved)
                                        <span class="text-center text-[9px] font-bold text-amber-500 uppercase">En attente</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-4 py-6 text-right rounded-r-3xl">
                                <div class="flex items-center justify-end gap-2">
                                    @if(!$comment->approved)
                                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Approuver">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                    @endif

                                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce commentaire ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm" title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-24 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-16 w-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    </div>
                                    <p class="text-slate-400 italic text-sm">Aucun commentaire ne correspond à votre recherche.</p>
                                    <a href="{{ route('admin.comments.index') }}" class="text-blue-500 text-xs font-bold mt-2 hover:underline">Réinitialiser les filtres</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-8">
                {{ $comments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
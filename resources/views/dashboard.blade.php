@extends('layouts.app')

@section('title', 'Mon Espace - ALDO NEWS')

@section('content')
<div class="py-12 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- En-tête avec message de bienvenue --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] font-bold uppercase tracking-widest">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}" class="text-slate-400 hover:text-cyan-500 transition-colors">Accueil</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <span class="text-slate-300 dark:text-slate-700 mx-2">/</span>
                                <span class="text-cyan-500">Espace Membre</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-4xl font-black mt-2 text-slate-900 dark:text-white tracking-tight">
                    Ravi de vous revoir, <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">{{ Auth::user()->name }}</span> 👋
                </h1>
            </div>
            
            <div class="flex gap-3">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-6 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm font-bold shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all text-slate-700 dark:text-white">
                    <i class="fas fa-user-cog mr-2 text-cyan-500"></i> Réglages Profil
                </a>
            </div>
        </div>

        {{-- Cartes de statistiques & Raccourcis --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            
            {{-- Carte 1 : Commentaires --}}
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 group hover:border-cyan-500/50 transition-all duration-300">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-slate-400 dark:text-slate-500 text-xs font-bold uppercase tracking-widest">Mon Activité</p>
                    <div class="p-2 bg-cyan-50 dark:bg-cyan-500/10 rounded-lg text-cyan-500">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-5xl font-black text-slate-900 dark:text-white">{{ $commentsCount }}</span>
                    <span class="text-slate-500 text-sm font-medium italic">réactions</span>
                </div>
            </div>

            {{-- Carte 2 : Raccourci Actualités (Nouveau) --}}
            <a href="{{ route('home') }}" class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 group hover:border-blue-500/50 transition-all duration-300 relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-slate-400 dark:text-slate-500 text-xs font-bold uppercase tracking-widest">Navigation</p>
                    <div class="p-2 bg-blue-50 dark:bg-blue-500/10 rounded-lg text-blue-500 group-hover:scale-110 transition-transform">
                        <i class="fas fa-newspaper"></i>
                    </div>
                </div>
                <div class="relative z-10">
                    <span class="text-2xl font-black text-slate-900 dark:text-white block">Lire les Actus</span>
                    <span class="text-blue-500 text-xs font-bold uppercase flex items-center mt-2">
                        Retourner au flux <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </div>
                <i class="fas fa-globe absolute -right-4 -bottom-4 text-slate-50 dark:text-slate-800/50 text-7xl rotate-12"></i>
            </a>

            {{-- Carte 3 : Statut Compte --}}
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 dark:from-blue-600 dark:to-cyan-500 p-8 rounded-[2.5rem] shadow-xl text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-white/60 text-xs font-bold uppercase tracking-widest mb-4">Rang Communauté</p>
                    <div class="flex items-center gap-3">
                        <span class="text-3xl font-black">{{ ucfirst(Auth::user()->role ?? 'Membre') }}</span>
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-bold uppercase border border-white/20">Compte Actif</span>
                    </div>
                </div>
                {{-- Décoration de fond --}}
                <div class="absolute -right-4 -bottom-4 text-white/10 text-8xl rotate-12 transition-transform group-hover:scale-110">
                    <i class="fas fa-award"></i>
                </div>
            </div>
        </div>

        {{-- Section Historique --}}
        <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-8 md:p-10 shadow-sm border border-slate-100 dark:border-slate-800 transition-colors">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                    <span class="w-2 h-8 bg-cyan-500 rounded-full"></span>
                    Mes derniers commentaires
                </h3>
            </div>
            
            <div class="space-y-4">
                @forelse($recentComments as $comment)
                    <div class="group flex items-start justify-between p-6 bg-slate-50 dark:bg-slate-800/40 rounded-3xl hover:bg-white dark:hover:bg-slate-800 transition-all border border-transparent hover:border-slate-200 dark:hover:border-slate-700 hover:shadow-xl dark:hover:shadow-none">
                        <div class="space-y-3 flex-1">
                            <p class="text-slate-700 dark:text-slate-300 font-medium leading-relaxed italic pr-4">
                                "{{ Str::limit($comment->content, 150) }}"
                            </p>
                            <div class="flex items-center gap-4">
                                <a href="{{ route('post.show', $comment->post->slug ?? '#') }}" class="text-[11px] text-cyan-600 dark:text-cyan-400 font-black uppercase tracking-widest flex items-center gap-1 hover:underline">
                                    <i class="fas fa-link text-[9px]"></i> {{ $comment->post->title ?? 'Contenu archivé' }}
                                </a>
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold whitespace-nowrap">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-slate-50 dark:bg-slate-800/20 rounded-[2rem] border-2 border-dashed border-slate-200 dark:border-slate-800">
                        <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-2xl">
                            🖋️
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 font-medium">Vous n'avez pas encore partagé d'avis.</p>
                        <a href="{{ route('home') }}" class="text-cyan-500 font-bold text-sm mt-4 inline-block hover:underline">Voir les actualités</a>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
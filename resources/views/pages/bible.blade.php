@extends('layouts.app')

@section('title', 'La Sainte Bible - ALDO NEWS')

@section('content')
<div class="py-12 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-500">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        
        {{-- Header --}}
        <div class="text-center mb-16 space-y-4">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 rounded-full text-xs font-black uppercase tracking-widest">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                </span>
                Méditation & Parole
            </div>
            <h1 class="text-5xl md:text-6xl font-black text-slate-900 dark:text-white tracking-tight">La Sainte Bible</h1>
            <p class="text-slate-500 dark:text-slate-400 max-w-xl mx-auto text-lg leading-relaxed font-serif italic">
                "Ta parole est une lampe à mes pieds, et une lumière sur mon sentier."
            </p>
        </div>

        {{-- Section Verset Dynamique --}}
        <div x-data="{ 
                active: 0,
                verses: [
                    { text: 'Tout ce que vous faites, faites-le de bon cœur, comme pour le Seigneur et non pour des hommes.', ref: 'Colossiens 3:23' },
                    { text: 'Car Dieu a tant aimé le monde qu’il a donné son Fils unique.', ref: 'Jean 3:16' },
                    { text: 'Le Seigneur est mon berger : je ne manque de rien.', ref: 'Psaume 23:1' },
                    { text: 'Ne t’inquiète de rien, mais en toute chose fais connaître tes besoins à Dieu.', ref: 'Philippiens 4:6' },
                    { text: 'Je puis tout par celui qui me fortifie.', ref: 'Philippiens 4:13' }
                ],
                next() { this.active = (this.active + 1) % this.verses.length },
                init() { setInterval(() => this.next(), 10000) } 
            }" 
            class="relative overflow-hidden mb-16 group">
            
            <div class="absolute inset-0 bg-gradient-to-r from-cyan-600 to-blue-700 blur-3xl opacity-10 group-hover:opacity-20 transition-opacity duration-700"></div>
            
            <div class="relative bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-white dark:border-slate-800 p-8 md:p-12 rounded-[3rem] shadow-2xl">
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="flex-1 space-y-6 text-center md:text-left h-40 flex flex-col justify-center">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-cyan-500">Verset du moment</span>
                            <button @click="next()" class="text-slate-400 hover:text-cyan-500 transition-colors">
                                <i class="fas fa-sync-alt text-xs animate-spin-slow"></i>
                            </button>
                        </div>

                        {{-- Animation de transition du texte --}}
                        <div class="relative">
                            <template x-for="(verse, index) in verses" :key="index">
                                <div x-show="active === index" 
                                     x-transition:enter="transition ease-out duration-500"
                                     x-transition:enter-start="opacity-0 translate-y-4"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-300 absolute top-0"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 -translate-y-4"
                                     class="space-y-4">
                                    <blockquote class="text-2xl md:text-3xl font-serif text-slate-800 dark:text-slate-100 leading-snug" x-text="'&ldquo;' + verse.text + '&rdquo;'"></blockquote>
                                    <cite class="block text-slate-500 font-bold not-italic" x-text="'— ' + verse.ref"></cite>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="w-32 h-32 flex items-center justify-center bg-slate-100 dark:bg-slate-800 rounded-3xl rotate-3 group-hover:rotate-6 transition-transform shadow-inner">
                        <span class="text-5xl">📖</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Système d'onglets pour les Livres --}}
        <div x-data="{ tab: 'ancien' }" class="space-y-10">
            <div class="flex justify-center p-1 bg-slate-200/50 dark:bg-slate-800/50 backdrop-blur rounded-2xl max-w-md mx-auto border border-slate-300/30 dark:border-slate-700/30">
                <button @click="tab = 'ancien'" :class="tab === 'ancien' ? 'bg-white dark:bg-slate-700 shadow-md text-cyan-500' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="flex-1 py-3 px-6 rounded-xl font-bold text-sm transition-all duration-300">
                    Ancien Testament
                </button>
                <button @click="tab = 'nouveau'" :class="tab === 'nouveau' ? 'bg-white dark:bg-slate-700 shadow-md text-cyan-500' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="flex-1 py-3 px-6 rounded-xl font-bold text-sm transition-all duration-300">
                    Nouveau Testament
                </button>
            </div>

            {{-- Grille des Livres --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                <template x-if="tab === 'ancien'">
                    <div class="contents">
                        @foreach(['Genèse', 'Exode', 'Lévitique', 'Nombres', 'Deutéronome', 'Josué', 'Juges', 'Ruth', 'Psaumes', 'Proverbes', 'Ésaïe', 'Jérémie'] as $livre)
                        <a href="#" class="group bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-cyan-500/50 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <div class="w-10 h-10 bg-slate-50 dark:bg-slate-800 rounded-xl flex items-center justify-center mb-4 text-xl group-hover:bg-cyan-500 group-hover:text-white transition-colors italic font-serif">
                                {{ substr($livre, 0, 1) }}
                            </div>
                            <h3 class="font-bold text-slate-900 dark:text-white">{{ $livre }}</h3>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-[9px] text-slate-400 uppercase tracking-widest font-bold group-hover:text-cyan-500 transition-colors">Explorer</span>
                                <i class="fas fa-arrow-right text-[8px] text-slate-300 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </template>

                <template x-if="tab === 'nouveau'">
                    <div class="contents">
                        @foreach(['Matthieu', 'Marc', 'Luc', 'Jean', 'Actes', 'Romains', 'Corinthiens', 'Galates', 'Éphésiens', 'Philippiens', 'Colossiens', 'Apocalypse'] as $livre)
                        <a href="#" class="group bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-blue-500/50 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <div class="w-10 h-10 bg-slate-50 dark:bg-slate-800 rounded-xl flex items-center justify-center mb-4 text-xl group-hover:bg-blue-600 group-hover:text-white transition-colors italic font-serif">
                                {{ substr($livre, 0, 1) }}
                            </div>
                            <h3 class="font-bold text-slate-900 dark:text-white">{{ $livre }}</h3>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-[9px] text-slate-400 uppercase tracking-widest font-bold group-hover:text-blue-500 transition-colors">Lire maintenant</span>
                                <i class="fas fa-arrow-right text-[8px] text-slate-300 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </template>
            </div>
        </div>

        {{-- Footer Méditation --}}
        <div class="mt-20 pt-10 border-t border-slate-200 dark:border-slate-800 text-center">
            <p class="text-slate-400 text-sm italic">"Le ciel et la terre passeront, mais mes paroles ne passeront point." — Luc 21:33</p>
        </div>
    </div>
</div>

<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 8s linear infinite;
    }
</style>
@endsection
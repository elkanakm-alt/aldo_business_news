@extends('layouts.app')

@section('title', 'À Propos - ALDO NEWS')

@section('content')
<div class="bg-white dark:bg-slate-950 transition-colors duration-500 overflow-hidden">
    
    {{-- Hero Section --}}
    <section class="relative py-24 px-4 overflow-hidden">
        {{-- Décoration d'arrière-plan --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-500/10 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[10%] right-[-5%] w-[30%] h-[30%] bg-cyan-500/10 blur-[100px] rounded-full"></div>
        </div>

        <div class="relative max-w-5xl mx-auto text-center">
            <span class="inline-block px-4 py-1.5 mb-6 text-[10px] font-black tracking-[0.3em] uppercase text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full">
                Notre Histoire
            </span>
            <h1 class="text-5xl md:text-7xl font-black text-slate-900 dark:text-white mb-8 tracking-tighter leading-tight">
                L'information qui <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">propulse votre vision.</span>
            </h1>
            <p class="max-w-2xl mx-auto text-lg md:text-xl text-slate-600 dark:text-slate-400 leading-relaxed font-light">
                ALDO_NEWS n'est pas qu'une plateforme d'actualités. C'est un écosystème conçu pour inspirer, informer et connecter les esprits curieux à travers un design d'exception.
            </p>
        </div>
    </section>

    {{-- Valeurs Section --}}
    <section class="py-20 px-4 bg-slate-50/50 dark:bg-slate-900/20">
        <div class="max-w-6xl mx-auto">
            <div class="grid md:grid-cols-3 gap-8">
                
                <div class="group relative p-8 bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                    <div class="w-14 h-14 mb-6 flex items-center justify-center bg-blue-50 dark:bg-blue-900/30 text-blue-600 rounded-2xl group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-rocket text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Innovation</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                        Nous repoussons les limites de la diffusion d'information en utilisant les dernières technologies web pour une fluidité absolue.
                    </p>
                </div>

                <div class="group relative p-8 bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                    <div class="w-14 h-14 mb-6 flex items-center justify-center bg-cyan-50 dark:bg-cyan-900/30 text-cyan-500 rounded-2xl group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-layer-group text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Expérience Premium</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                        Chaque pixel est pensé pour votre confort visuel, offrant une lecture immersive que ce soit en mode clair ou sombre.
                    </p>
                </div>

                <div class="group relative p-8 bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                    <div class="w-14 h-14 mb-6 flex items-center justify-center bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 rounded-2xl group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-globe-africa text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Impact Global</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                        Notre mission est de connecter les communautés locales aux enjeux mondiaux à travers un journalisme de qualité.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- Stats/Mantra Section --}}
    <section class="py-24 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="relative p-1 bg-gradient-to-r from-blue-600 via-cyan-500 to-indigo-600 rounded-[3rem]">
                <div class="bg-white dark:bg-slate-950 p-10 md:p-16 rounded-[2.9rem] text-center">
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-6">
                        Plus qu'une simple news, <br>une nouvelle perspective.
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 mb-10 font-serif italic text-lg">
                        "Informer est un devoir, inspirer est notre passion."
                    </p>
                    <div class="flex flex-wrap justify-center gap-8">
                        <div class="text-center">
                            <span class="block text-3xl font-black text-blue-600">5k+</span>
                            <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Lecteurs</span>
                        </div>
                        <div class="w-px h-10 bg-slate-200 dark:bg-slate-800 hidden sm:block"></div>
                        <div class="text-center">
                            <span class="block text-3xl font-black text-cyan-500">100%</span>
                            <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Passion</span>
                        </div>
                        <div class="w-px h-10 bg-slate-200 dark:bg-slate-800 hidden sm:block"></div>
                        <div class="text-center">
                            <span class="block text-3xl font-black text-indigo-500">24/7</span>
                            <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Actualité</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 text-center">
        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Prêt à nous suivre ?</h3>
        {{-- ICI LE LIEN CORRIGÉ --}}
        <a href="{{ route('contact.show') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-full font-black text-sm transition-all hover:scale-105 hover:shadow-xl active:scale-95">
            NOUS CONTACTER
            <i class="fas fa-arrow-right text-xs"></i>
        </a>
    </section>
</div>
@endsection
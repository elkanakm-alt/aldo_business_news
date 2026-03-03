@extends('layouts.app')

@section('title', 'Inscription - ALDO NEWS')

@section('content')
<div class="min-h-[90vh] flex items-center justify-center px-4 py-12 relative overflow-hidden">
    <div class="absolute top-20 right-0 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>

    <div class="w-full max-w-lg">
        <div class="bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl p-8 md:p-12 rounded-[2.5rem] shadow-2xl border border-white/20 dark:border-slate-800/50">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-black mb-3 bg-gradient-to-r from-cyan-500 to-blue-600 bg-clip-text text-transparent">Rejoignez l'élite</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Créez votre accès Premium à ALDO_NEWS</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-widest mb-2 ml-1 text-slate-600 dark:text-slate-400">Nom Complet</label>
                    <input type="text" name="name" required 
                           class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition-all shadow-sm"
                           placeholder="John Doe">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-widest mb-2 ml-1 text-slate-600 dark:text-slate-400">Email</label>
                    <input type="email" name="email" required 
                           class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition-all shadow-sm"
                           placeholder="john@exemple.com">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest mb-2 ml-1 text-slate-600 dark:text-slate-400">Mot de passe</label>
                    <input type="password" name="password" required 
                           class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition-all shadow-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest mb-2 ml-1 text-slate-600 dark:text-slate-400">Confirmation</label>
                    <input type="password" name="password_confirmation" required 
                           class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition-all shadow-sm">
                </div>

                <div class="md:col-span-2 pt-4">
                    <button type="submit" 
                            class="w-full py-4 bg-slate-900 dark:bg-cyan-500 text-white rounded-2xl font-bold shadow-xl hover:bg-black dark:hover:bg-cyan-600 hover:-translate-y-1 transition-all duration-300">
                        CRÉER MON COMPTE
                    </button>
                </div>
            </form>

            <div class="mt-10 pt-8 border-t border-slate-100 dark:border-slate-800 text-center">
                <p class="text-sm text-slate-500">
                    Déjà membre ? 
                    <a href="{{ route('login') }}" class="text-cyan-500 font-bold hover:underline ml-1">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
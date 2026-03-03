@extends('layouts.app')

@section('title', 'Connexion - ALDO NEWS')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 relative overflow-hidden">
    <div class="absolute top-0 -left-20 w-72 h-72 bg-cyan-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 -right-20 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>

    <div class="w-full max-w-md">
        <div class="bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl p-8 md:p-10 rounded-3xl shadow-2xl border border-white/20 dark:border-slate-800/50">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-black mb-2 bg-gradient-to-r from-cyan-500 to-blue-600 bg-clip-text text-transparent">Bon retour !</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Ravi de vous revoir sur ALDO_NEWS</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest mb-2 ml-1 text-slate-600 dark:text-slate-400">Email professionnel</label>
                    <input type="email" name="email" required 
                           class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition-all duration-300 shadow-sm placeholder:text-slate-400"
                           placeholder="nom@exemple.com">
                </div>

                <div>
                    <div class="flex justify-between mb-2 ml-1">
                        <label class="text-xs font-bold uppercase tracking-widest text-slate-600 dark:text-slate-400">Mot de passe</label>
                        <a href="{{ route('password.request') }}" class="text-xs font-bold text-cyan-500 hover:text-cyan-600">Oublié ?</a>
                    </div>
                    <input type="password" name="password" required 
                           class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition-all duration-300 shadow-sm">
                </div>

                <div class="flex items-center ml-1">
                    <input type="checkbox" name="remember" id="remember" class="rounded text-cyan-500 focus:ring-cyan-500 bg-slate-100 border-slate-300">
                    <label for="remember" class="ml-2 text-sm text-slate-500">Se souvenir de moi</label>
                </div>

                <button type="submit" 
                        class="w-full py-4 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-2xl font-bold shadow-xl shadow-cyan-500/20 hover:shadow-cyan-500/40 hover:-translate-y-1 transition-all duration-300 active:scale-95">
                    SE CONNECTER
                </button>
            </form>

            <div class="mt-10 text-center">
                <p class="text-sm text-slate-500">
                    Pas encore de compte ? 
                    <a href="{{ route('register') }}" class="text-cyan-500 font-bold hover:underline ml-1">Créer un compte</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
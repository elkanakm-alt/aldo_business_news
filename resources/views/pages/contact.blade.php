@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-16 px-4 transition-colors duration-500">
    <div class="max-w-4xl mx-auto">
        
        {{-- En-tête Premium --}}
        <div class="text-center mb-16">
            <h1 class="text-6xl font-black tracking-tighter uppercase mb-4">
                <span class="text-slate-900 dark:text-white">Contactez</span> 
                <span class="text-blue-600">AL</span><span class="text-red-600">DO</span><span class="text-blue-600">_NEWS</span>
            </h1>
            <div class="flex items-center justify-center gap-4">
                <div class="h-px w-12 bg-blue-600/30"></div>
                <p class="text-slate-500 dark:text-slate-400 font-bold uppercase text-[10px] tracking-[0.4em]">
                    Service Support Premium
                </p>
                <div class="h-px w-12 bg-red-600/30"></div>
            </div>
        </div>

        {{-- Alertes de succès ou d'erreur --}}
        @if(session('success'))
            <div class="mb-8 p-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-3xl text-center font-black animate-pulse">
                ✨ {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 p-6 bg-red-500/10 border border-red-500/20 text-red-500 rounded-3xl text-center font-bold">
                @foreach ($errors->all() as $error)
                    <p>⚠️ {{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Carte de Formulaire Premium --}}
        <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-red-600 rounded-[3rem] blur opacity-10 group-hover:opacity-20 transition duration-1000"></div>
            
            <div class="relative bg-white dark:bg-slate-900/80 backdrop-blur-xl p-8 md:p-12 rounded-[3rem] shadow-2xl border border-slate-200 dark:border-white/10">
                
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Nom --}}
                        <div class="relative">
                            <label class="text-[10px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400 mb-2 block ml-4">Nom Complet</label>
                            <input type="text" name="name" value="{{ old('name') }}" required 
                                class="w-full bg-slate-100 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-slate-800 transition-all font-bold text-slate-900 dark:text-white"
                                placeholder="Ex: John Doe">
                        </div>
                        
                        {{-- Email --}}
                        <div class="relative">
                            <label class="text-[10px] font-black uppercase tracking-widest text-red-600 dark:text-red-400 mb-2 block ml-4">Adresse Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                class="w-full bg-slate-100 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-red-500 focus:bg-white dark:focus:bg-slate-800 transition-all font-bold text-slate-900 dark:text-white"
                                placeholder="john@example.com">
                        </div>
                    </div>

                    {{-- Sujet --}}
                    <div class="relative">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-4">Objet du message</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required 
                            class="w-full bg-slate-100 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-slate-800 transition-all font-bold text-slate-900 dark:text-white">
                    </div>

                    {{-- Message --}}
                    <div class="relative">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-4">Votre Message</label>
                        <textarea name="message" rows="5" required 
                            class="w-full bg-slate-100 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-slate-800 transition-all font-bold text-slate-900 dark:text-white"
                            placeholder="Comment pouvons-nous vous aider ?">{{ old('message') }}</textarea>
                    </div>

                    {{-- GOOGLE RECAPTCHA CASE --}}
                    <div class="flex justify-center py-4">
                        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    </div>

                    {{-- Bouton Submit --}}
                    <button type="submit" class="group relative w-full inline-flex items-center justify-center px-8 py-5 font-black uppercase tracking-widest text-white transition-all duration-200 bg-slate-900 dark:bg-white dark:text-slate-900 rounded-2xl overflow-hidden">
                        <div class="absolute inset-0 w-full h-full transition-all duration-300 scale-0 group-hover:scale-100 group-hover:bg-gradient-to-r from-blue-600 to-red-600 rounded-2xl"></div>
                        <span class="relative group-hover:text-white">Envoyer le message premium</span>
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- SCRIPT RECAPTCHA --}}
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
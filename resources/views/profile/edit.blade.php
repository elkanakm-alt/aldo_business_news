@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-12 px-4">
    <div class="max-w-3xl mx-auto">
        
        {{-- En-tête de page --}}
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                    Réglages <span class="text-rose-500 text-glow-red">Profil</span>
                </h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium mt-1 uppercase text-[10px] tracking-[0.2em]">
                    Identité & Expérience Premium
                </p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-rose-500/10 flex items-center justify-center text-rose-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-2xl flex items-center gap-3 animate-fade-in">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="space-y-8">
            @csrf
            @method('PATCH')

            {{-- SECTION PHOTO AVEC DÉGRADÉ ROUGE/BLEU --}}
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
                <div class="flex flex-col md:flex-row items-center gap-10">
                    <div class="relative group">
                        {{-- Cercle d'animation ROUGE / BLEU (Premium) --}}
                        <div class="absolute -inset-2 bg-gradient-to-tr from-rose-600 via-purple-500 to-blue-600 rounded-[2.8rem] animate-spin-slow opacity-60 group-hover:opacity-100 transition duration-700 blur-[1px]"></div>
                        
                        {{-- Preview Image --}}
                        <div class="relative h-44 w-44 rounded-[2.5rem] bg-slate-100 dark:bg-slate-800 border-4 border-white dark:border-slate-900 overflow-hidden shadow-2xl">
                            <img src="{{ auth()->user()->photo ? asset('storage/'.auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.auth()->user()->name }}" 
                                 id="avatar-preview"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            
                            {{-- Overlay Upload --}}
                            <label for="photo-upload" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer backdrop-blur-sm">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </label>
                        </div>
                        <input type="file" name="photo" id="photo-upload" class="hidden" onchange="previewFile(event)">
                    </div>

                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Style Signature</h3>
                        <p class="text-slate-500 text-sm mt-2 leading-relaxed italic">
                            Personnalisez votre apparence publique.
                        </p>
                        <div class="mt-5 flex flex-wrap justify-center md:justify-start gap-3">
                            <span class="px-4 py-1.5 bg-rose-500/10 text-rose-600 text-[10px] font-black uppercase tracking-widest rounded-xl border border-rose-500/20 shadow-sm">JPG / JPEG</span>
                            <span class="px-4 py-1.5 bg-blue-500/10 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-xl border border-blue-500/20 shadow-sm">PNG</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION INFORMATIONS --}}
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
                <div class="space-y-6">
                    <div class="relative">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-3 block ml-2">Identifiant Public</label>
                        <div class="relative group">
                            <input type="text" 
                                   name="name" 
                                   value="{{ auth()->user()->name }}"
                                   class="w-full bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-rose-500 focus:bg-white dark:focus:bg-slate-900 rounded-2xl px-6 py-5 font-bold text-slate-900 dark:text-white transition-all duration-300 outline-none shadow-inner"
                                   placeholder="Votre nom...">
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-rose-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" 
                                class="w-full relative group overflow-hidden bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black uppercase tracking-widest text-xs py-5 rounded-2xl transition-all duration-500 hover:shadow-[0_20px_50px_rgba(244,63,94,0.3)] hover:-translate-y-1 active:scale-[0.97]">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                Valider les modifications
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                            {{-- Dégradé Rouge/Bleu au survol du bouton --}}
                            <div class="absolute inset-0 bg-gradient-to-r from-rose-600 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .text-glow-red { text-shadow: 0 0 20px rgba(244, 63, 94, 0.5); }
    @keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .animate-spin-slow { animation: spin-slow 10s linear infinite; }
</style>

<script>
    function previewFile(event) {
        const input = event.target;
        const preview = document.getElementById('avatar-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) { preview.src = e.target.result; }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
@extends('layouts.app')

@section('content')
@php 
    \Carbon\Carbon::setLocale('fr');
    $wordCount = str_word_count(strip_tags($post->content));
    $readingTime = ceil($wordCount / 200); 
    $commentsList = isset($comments) ? $comments : $post->comments()->whereNull('parent_id')->where('status', 'approved')->latest()->get();
@endphp

{{-- Container global très large --}}
<div class="max-w-[1550px] mx-auto px-4 sm:px-6 lg:px-10 py-6 md:py-12">
    
    <div class="flex flex-col lg:flex-row gap-8 md:gap-12">
        
        {{-- COLONNE LECTURE (Prend le maximum d'espace) --}}
        <article class="flex-1 w-full">
            
            {{-- TITRE --}}
            <h1 class="text-3xl md:text-6xl font-black leading-tight mb-8 text-slate-900 dark:text-white italic tracking-tighter">
                {{ $post->title }}
            </h1>

            {{-- DIV PRINCIPALE (Adaptative Light/Dark) --}}
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-100 dark:border-slate-800 rounded-[2rem] md:rounded-[3rem] overflow-hidden">
                
                {{-- PHOTO PRINCIPALE (Format d'origine) --}}
                <div class="w-full bg-slate-50 dark:bg-slate-800">
                    <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/default.jpg') }}" 
                         class="w-full h-auto max-h-[75vh] object-contain mx-auto" 
                         alt="{{ $post->title }}">
                </div>

                {{-- ZONE DE TEXTE ÉLARGIE AU MAXIMUM --}}
                <div class="p-5 md:p-10">
                    
                    {{-- Barre d'infos --}}
                    <div class="flex items-center justify-between mb-8 pb-6 border-b border-slate-50 dark:border-slate-800">
                        <div class="flex items-center gap-4">
                            <img src="{{ $post->user && $post->user->photo ? asset('storage/'.$post->user->photo) : 'https://ui-avatars.com/api/?background=10b981&color=fff&name='.urlencode($post->user->name ?? 'A') }}" 
                                 class="w-12 h-12 rounded-full border-2 border-emerald-500 object-cover">
                            <div class="leading-none">
                                <p class="font-black text-slate-900 dark:text-white text-[13px] uppercase mb-1">{{ $post->user->name ?? 'Auteur' }}</p>
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">{{ $post->created_at->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <button onclick="likePost({{ $post->id }})" class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 px-5 py-2 rounded-xl active:scale-125 transition font-black text-[12px]">
                            ❤️ <span id="like-count-{{ $post->id }}">{{ $post->likes ?? 0 }}</span>
                        </button>
                    </div>

                    {{-- CONTENU : Ici on force la pleine largeur avec max-w-none --}}
                    <div class="article-content prose dark:prose-invert max-w-none w-full text-lg md:text-[22px] leading-[1.8] text-slate-700 dark:text-slate-300 font-sans">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>

            {{-- SECTION DISCUSSION --}}
            <section class="bg-white dark:bg-slate-900 shadow-sm border border-slate-100 dark:border-slate-800 rounded-[2rem] md:rounded-[3rem] p-6 md:p-10 mt-10">
                <h3 class="text-2xl font-black uppercase italic mb-8 dark:text-white tracking-tighter">Discussion</h3>
                {{-- ... Tes commentaires ici --}}
            </section>
        </article>

        {{-- SIDEBAR (Plus fine pour laisser de la place au texte) --}}
        <aside class="w-full lg:w-[320px] shrink-0">
            <div class="lg:sticky lg:top-24">
                @include('profile.partials.sidebar')
            </div>
        </aside>
    </div>
</div>

<style>
    /* On s'assure que le contenu HTML généré (images, tableaux) prend toute la largeur */
    .article-content > * {
        width: 100% !important;
        max-width: 100% !important;
    }
    .article-content img {
        height: auto;
        border-radius: 1.5rem;
        margin: 2.5rem 0;
    }
    .article-content p { margin-bottom: 1.5rem; }
</style>
@endsection
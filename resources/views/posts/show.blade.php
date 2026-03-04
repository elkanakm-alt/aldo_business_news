@extends('layouts.app')

@section('content')
@php 
    \Carbon\Carbon::setLocale('fr');
    $wordCount = str_word_count(strip_tags($post->content));
    $readingTime = ceil($wordCount / 200); 
    $commentsList = isset($comments) ? $comments : $post->comments()->whereNull('parent_id')->where('status', 'approved')->latest()->get();
@endphp

<div class="max-w-[1550px] mx-auto px-0 sm:px-6 lg:px-10 bg-white dark:bg-slate-950 min-h-screen">
    
    {{-- 1. CADRE PHOTO : Utilise object-contain pour NE JAMAIS couper --}}
    <div class="w-full mb-8 md:mb-14 md:pt-6">
        <div class="w-full h-72 md:h-[70vh] bg-slate-100 dark:bg-slate-900 overflow-hidden md:rounded-[3.5rem] shadow-2xl flex items-center justify-center">
            <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/default.jpg') }}" 
                 class="w-full h-full object-contain" {{-- Ici : Contain garantit l'image entière --}}
                 alt="{{ $post->title }}">
        </div>
    </div>

    <div class="px-4 sm:px-0">
        <div class="flex flex-col lg:flex-row gap-8 md:gap-16">
            
            {{-- 2. ZONE DE LECTURE : Largeur maximale --}}
            <article class="flex-1 w-full">
                
                <h1 class="text-3xl md:text-7xl font-black leading-tight mb-8 text-slate-900 dark:text-white italic tracking-tighter">
                    {{ $post->title }}
                </h1>

                {{-- BARRE DE STATS : Vues, Likes, Lecture --}}
                <div class="flex flex-wrap items-center justify-between py-8 border-y border-slate-100 dark:border-slate-800 mb-12 gap-6">
                    <div class="flex items-center gap-4">
                        <img src="{{ $post->user && $post->user->photo ? asset('storage/'.$post->user->photo) : 'https://ui-avatars.com/api/?background=10b981&color=fff&name='.urlencode($post->user->name ?? 'A') }}" 
                             class="w-14 h-14 rounded-full border-2 border-emerald-500 shadow-sm object-cover">
                        <div>
                            <p class="font-black text-slate-900 dark:text-white text-[14px] uppercase tracking-tighter">{{ $post->user->name ?? 'Auteur' }}</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">{{ $post->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-6 text-[11px] font-black uppercase tracking-widest text-slate-500">
                        <span class="flex items-center gap-2">⏱ {{ $readingTime }} MIN</span>
                        <span class="flex items-center gap-2">👁 {{ $post->views ?? 0 }} VUES</span>
                        <button onclick="likePost({{ $post->id }})" class="flex items-center gap-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 px-5 py-2.5 rounded-xl active:scale-125 transition">
                            ❤️ <span id="like-count-{{ $post->id }}">{{ $post->likes ?? 0 }}</span>
                        </button>
                    </div>
                </div>

                {{-- CONTENU : Sans div restrictive, prend toute la largeur --}}
                <div class="article-content prose dark:prose-invert max-w-none w-full text-lg md:text-[25px] leading-[1.85] text-slate-700 dark:text-slate-300 font-sans">
                    {!! $post->content !!}
                </div>

                {{-- COMMENTAIRES --}}
                <section class="mt-20 pt-10 border-t border-slate-100 dark:border-slate-800 pb-20">
                    <h3 class="text-3xl font-black uppercase italic mb-10 dark:text-white tracking-tighter">Discussion</h3>
                    {{-- Ton formulaire de commentaire ici --}}
                </section>
            </article>

            {{-- 3. SIDEBAR --}}
            <aside class="w-full lg:w-[350px] shrink-0">
                <div class="lg:sticky lg:top-24">
                    @include('profile.partials.sidebar')
                </div>
            </aside>
        </div>
    </div>
</div>

<script>
function likePost(postId) {
    fetch('/post/' + postId + '/like', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => { if(data.likes !== undefined) document.getElementById('like-count-' + postId).innerText = data.likes; })
}
</script>

<style>
    /* Images internes : elles aussi ne sont jamais coupées */
    .article-content img {
        width: 100%;
        height: auto;
        object-fit: contain;
        border-radius: 2rem;
        margin: 3.5rem 0;
    }
    .article-content p { width: 100%; margin-bottom: 2rem; }
</style>
@endsection
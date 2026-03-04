@extends('layouts.app')

@section('content')
@php 
    \Carbon\Carbon::setLocale('fr');
    $wordCount = str_word_count(strip_tags($post->content));
    $readingTime = ceil($wordCount / 200); 
    $commentsList = isset($comments) ? $comments : $post->comments()->whereNull('parent_id')->where('status', 'approved')->latest()->get();
@endphp

<div class="max-w-[1550px] mx-auto px-0 sm:px-6 lg:px-10 py-6 md:py-12 bg-white dark:bg-slate-950 min-h-screen">
    
    {{-- 1. PHOTO PRINCIPALE : Cadre arrondi moderne sans coupure --}}
    <div class="w-full mb-8 md:mb-14 md:pt-4 px-4 sm:px-0">
        <div class="w-full bg-slate-100 dark:bg-slate-900 rounded-[2.5rem] md:rounded-[4rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
            <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/default.jpg') }}" 
                 class="w-full h-auto max-h-[75vh] object-contain mx-auto shadow-inner" 
                 alt="{{ $post->title }}">
        </div>
    </div>

    <div class="px-5 sm:px-0">
        <div class="flex flex-col lg:flex-row gap-8 md:gap-16">
            
            <article class="flex-1 w-full">
                
                {{-- Titre --}}
                <h1 class="text-3xl md:text-7xl font-black leading-[1.1] mb-8 text-slate-900 dark:text-white italic tracking-tighter">
                    {{ $post->title }}
                </h1>

                {{-- Barre d'infos & Compteurs --}}
                <div class="flex flex-wrap items-center justify-between py-8 border-y border-slate-100 dark:border-slate-800 mb-12 gap-6">
                    <div class="flex items-center gap-4">
                        <img src="{{ $post->user && $post->user->photo ? asset('storage/'.$post->user->photo) : 'https://ui-avatars.com/api/?background=10b981&color=fff&name='.urlencode($post->user->name ?? 'A') }}" 
                             class="w-14 h-14 rounded-full border-2 border-emerald-500 object-cover shadow-sm">
                        <div>
                            <p class="font-black text-slate-900 dark:text-white text-[14px] uppercase tracking-tighter leading-none mb-1">{{ $post->user->name ?? 'Auteur' }}</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">{{ $post->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-6 text-[11px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <span class="flex items-center gap-2">⏱ {{ $readingTime }} MIN</span>
                        <span class="flex items-center gap-2">👁 {{ $post->views ?? 0 }} VUES</span>
                        <button onclick="likePost({{ $post->id }})" class="flex items-center gap-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 px-5 py-2.5 rounded-xl active:scale-125 transition">
                            ❤️ <span id="like-count-{{ $post->id }}">{{ $post->likes ?? 0 }}</span>
                        </button>
                    </div>
                </div>

                {{-- CONTENU TEXTUEL : Pleine largeur --}}
                <div class="article-content prose dark:prose-invert max-w-none w-full text-lg md:text-[24px] leading-[1.85] text-slate-700 dark:text-slate-300 font-sans">
                    {!! $post->content !!}
                </div>

                {{-- SECTION DISCUSSION --}}
                <section class="mt-20 pt-10 border-t border-slate-100 dark:border-slate-800 pb-20">
                    <div class="flex items-center justify-between mb-10">
                        <h3 class="text-3xl font-black uppercase italic dark:text-white tracking-tighter">Discussion</h3>
                        <span class="bg-emerald-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase">{{ $commentsList->count() }} avis</span>
                    </div>

                    @auth
                        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-12">
                            @csrf
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-6 rounded-[2.5rem] flex flex-col gap-4 border border-slate-100 dark:border-slate-800">
                                <textarea name="content" rows="3" class="w-full bg-transparent border-none focus:ring-0 text-lg dark:text-white placeholder:text-slate-400" placeholder="Votre avis sur cet article..."></textarea>
                                <button type="submit" class="self-end px-8 py-4 bg-slate-900 dark:bg-emerald-600 text-white text-[11px] font-black uppercase rounded-xl shadow-xl hover:scale-105 transition">Envoyer</button>
                            </div>
                        </form>
                    @else
                        {{-- MESSAGE POUR LES NON-CONNECTÉS --}}
                        <div class="mb-12 p-8 rounded-[2.5rem] bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30 text-center">
                            <p class="text-slate-600 dark:text-slate-400 font-bold mb-4">Vous voulez participer à la discussion ?</p>
                            <div class="flex flex-wrap justify-center gap-4">
                                <a href="{{ route('login') }}" class="px-6 py-2.5 bg-emerald-600 text-white text-[10px] font-black uppercase rounded-full shadow-lg hover:bg-emerald-700 transition">Se connecter</a>
                                <a href="{{ route('register') }}" class="px-6 py-2.5 bg-white dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 text-[10px] font-black uppercase rounded-full hover:bg-emerald-50 transition">Créer un compte</a>
                            </div>
                        </div>
                    @endauth

                    {{-- Liste des commentaires --}}
                    <div class="space-y-8">
                        @forelse($commentsList as $comment)
                            <div class="flex gap-5">
                                <img src="{{ $comment->user->photo ? asset('storage/'.$comment->user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name) }}" class="w-12 h-12 rounded-full border border-slate-200 object-cover shrink-0">
                                <div class="flex-1 bg-slate-50 dark:bg-slate-900/30 p-6 rounded-[2.5rem] border border-slate-50 dark:border-slate-800/50">
                                    <div class="flex justify-between items-center mb-2">
                                        <h4 class="text-[12px] font-black uppercase dark:text-white">{{ $comment->user->name }}</h4>
                                        <span class="text-[9px] text-slate-400 font-bold tracking-tighter">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-lg text-slate-600 dark:text-slate-400 italic leading-relaxed">"{{ $comment->content }}"</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-400 text-sm italic py-10">Soyez le premier à donner votre avis.</p>
                        @endforelse
                    </div>
                </section>
            </article>

            {{-- Sidebar --}}
            <aside class="w-full lg:w-[320px] shrink-0">
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
    .article-content { width: 100% !important; }
    .article-content p { margin-bottom: 2rem; width: 100%; }
    .article-content img {
        width: 100%;
        height: auto;
        object-fit: contain;
        border-radius: 2rem;
        margin: 3.5rem 0;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
    }
</style>
@endsection
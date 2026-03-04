@extends('layouts.app')

@section('content')
@php 
    \Carbon\Carbon::setLocale('fr');
    $wordCount = str_word_count(strip_tags($post->content));
    $readingTime = ceil($wordCount / 200); 
    $commentsList = isset($comments) ? $comments : $post->comments()->whereNull('parent_id')->where('status', 'approved')->latest()->get();
@endphp

{{-- Container principal : px-0 sur mobile pour coller aux bords --}}
<div class="max-w-[1500px] mx-auto px-0 sm:px-6 lg:px-10 bg-white dark:bg-slate-950 min-h-screen">
    
    {{-- 1. IMAGE PRINCIPALE : Pas d'arrondis sur mobile pour un look "Full Screen" --}}
    <div class="w-full mb-6 md:mb-12 md:pt-6">
        <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/default.jpg') }}" 
             class="w-full h-72 md:h-[65vh] object-cover md:rounded-[3rem] shadow-2xl">
    </div>

    {{-- On remet un petit padding horizontal UNIQUEMENT pour le texte --}}
    <div class="px-4 sm:px-0">
        <div class="flex flex-col lg:flex-row gap-8 md:gap-16">
            
            {{-- 2. COLONNE LECTURE : On utilise 70% de l'espace sur PC --}}
            <article class="flex-1 lg:max-w-[70%]">
                
                {{-- Titre --}}
                <div class="mb-8">
                    <h1 class="text-3xl md:text-6xl font-black leading-tight mb-6 text-slate-900 dark:text-white italic tracking-tighter">
                        {{ $post->title }}
                    </h1>

                    {{-- Barre d'infos --}}
                    <div class="flex items-center justify-between py-6 border-y border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <img src="{{ $post->user && $post->user->photo ? asset('storage/'.$post->user->photo) : 'https://ui-avatars.com/api/?background=10b981&color=fff&name='.urlencode($post->user->name ?? 'A') }}" 
                                 class="w-12 h-12 rounded-full border-2 border-emerald-500 object-cover">
                            <div class="leading-none">
                                <p class="font-black text-slate-900 dark:text-white text-[12px] uppercase mb-1">{{ $post->user->name ?? 'Auteur' }}</p>
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">{{ $post->created_at->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <button onclick="likePost({{ $post->id }})" class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 px-4 py-2 rounded-xl active:scale-125 transition font-black text-[11px]">
                                ❤️ <span id="like-count-{{ $post->id }}">{{ $post->likes ?? 0 }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CONTENU : max-w-none indispensable pour l'élargissement --}}
                <div class="article-content prose dark:prose-invert max-w-none text-[17px] md:text-2xl leading-[1.8] text-slate-700 dark:text-slate-300 font-sans">
                    {!! $post->content !!}
                </div>

                {{-- SECTION DISCUSSION --}}
                <section class="mt-16 pt-10 border-t border-slate-100 dark:border-slate-800">
                    <h3 class="text-2xl font-black uppercase italic mb-8 dark:text-white">Discussion</h3>

                    @auth
                        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-10">
                            @csrf
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl flex flex-col gap-4">
                                <textarea name="content" rows="3" class="w-full bg-transparent border-none focus:ring-0 text-base dark:text-white" placeholder="Votre avis..."></textarea>
                                <button type="submit" class="self-end px-6 py-3 bg-emerald-600 text-white text-[11px] font-black uppercase rounded-xl shadow-lg">Envoyer</button>
                            </div>
                        </form>
                    @endauth

                    <div class="space-y-6">
                        @forelse($commentsList as $comment)
                            <div class="flex gap-4 py-4">
                                <img src="{{ $comment->user->photo ? asset('storage/'.$comment->user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name) }}" class="w-10 h-10 rounded-full border border-slate-200">
                                <div class="flex-1 bg-slate-50 dark:bg-slate-900/30 p-5 rounded-2xl">
                                    <h4 class="text-[11px] font-black uppercase dark:text-white mb-1">{{ $comment->user->name }}</h4>
                                    <p class="text-base text-slate-600 dark:text-slate-400 italic">"{{ $comment->content }}"</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-400 text-sm italic py-6">Aucun avis pour le moment.</p>
                        @endforelse
                    </div>
                </section>
            </article>

            {{-- 3. SIDEBAR --}}
            <aside class="w-full lg:w-[320px] shrink-0 pb-10">
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
    /* Elargissement des images dans le texte sur mobile */
    .article-content img {
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        max-width: none;
        height: auto;
        border-radius: 0;
    }
    
    @media (min-width: 768px) {
        .article-content img {
            width: 100%;
            margin-left: 0;
            margin-right: 0;
            left: 0;
            right: 0;
            border-radius: 2rem;
        }
    }
    
    .article-content p { margin-bottom: 1.8rem; }
</style>
@endsection
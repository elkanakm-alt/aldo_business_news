@extends('layouts.app')

@section('content')
@php 
    \Carbon\Carbon::setLocale('fr');
    $wordCount = str_word_count(strip_tags($post->content));
    $readingTime = ceil($wordCount / 200); 
    $commentsList = isset($comments) ? $comments : $post->comments()->whereNull('parent_id')->where('status', 'approved')->latest()->get();
@endphp

{{-- Utilisation d'un container large pour éviter l'effet "trop au milieu" --}}
<div class="max-w-[1350px] mx-auto px-2 md:px-8 py-6 md:py-10">
    <div class="grid lg:grid-cols-3 gap-10">
        {{-- COLONNE LECTURE --}}
        <article class="lg:col-span-2">
            
            {{-- Image principale : RETOUR AU DESIGN D'ORIGINE --}}
            <div class="mb-8">
                <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/default.jpg') }}" 
                     class="w-full h-64 md:h-[55vh] object-cover rounded-[2.5rem] shadow-xl transition-transform duration-500 hover:scale-[1.01]">
            </div>

            {{-- CADRE DE TEXTE ÉLARGI --}}
            <div class="px-2 md:px-6">
                <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-6 text-slate-900 dark:text-white italic tracking-tighter">
                    {{ $post->title }}
                </h1>

                {{-- Barre d'infos --}}
                <div class="flex flex-wrap items-center justify-between gap-4 mb-10 pb-6 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <img src="{{ $post->user && $post->user->photo ? asset('storage/'.$post->user->photo) : 'https://ui-avatars.com/api/?background=10b981&color=fff&name='.urlencode($post->user->name ?? 'A') }}" 
                             class="w-12 h-12 rounded-full border-2 border-emerald-500 shadow-sm object-cover">
                        <div class="font-sans">
                            <p class="font-bold text-slate-900 dark:text-white text-sm uppercase leading-none mb-1">{{ $post->user->name ?? 'Auteur' }}</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">{{ $post->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 text-[11px] font-bold uppercase text-slate-500">
                        <span class="bg-slate-50 dark:bg-slate-800 px-4 py-2 rounded-xl border border-slate-100">⏱ {{ $readingTime }} min</span>
                        <button onclick="likePost({{ $post->id }})" class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 px-4 py-2 rounded-xl active:scale-125 transition shadow-sm">
                            ❤️ <span id="like-count-{{ $post->id }}">{{ $post->likes ?? 0 }}</span>
                        </button>
                    </div>
                </div>

                {{-- CONTENU ÉLARGI : Changement de max-w-3xl à max-w-5xl --}}
                <div class="article-box prose dark:prose-invert max-w-5xl mx-auto text-lg md:text-xl leading-relaxed mb-16 text-slate-700 dark:text-slate-300 font-sans">
                    {!! $post->content !!}
                </div>

                {{-- SECTION DISCUSSION ÉLARGIE AUSSI --}}
                <section class="mt-10 pt-10 border-t border-slate-100 dark:border-slate-800 max-w-5xl mx-auto">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-2xl font-black uppercase italic dark:text-white tracking-tighter">Discussion</h3>
                        <span class="text-xs font-bold px-4 py-1.5 bg-emerald-500 text-white rounded-full uppercase shadow-lg shadow-emerald-500/20">
                            {{ $commentsList->count() }} avis
                        </span>
                    </div>

                    @auth
                        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-12 bg-white dark:bg-slate-800/40 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm">
                            @csrf
                            <div class="flex gap-4">
                                <img src="{{ auth()->user()->photo ? asset('storage/'.auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=10b981&color=fff' }}" class="w-10 h-10 rounded-xl object-cover">
                                <div class="flex-1">
                                    <textarea name="content" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-base focus:ring-2 focus:ring-emerald-500/20 dark:text-white transition-all" placeholder="Écrivez votre avis ici..."></textarea>
                                    <div class="flex justify-end mt-3">
                                        <button type="submit" class="px-8 py-3 bg-slate-900 dark:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:scale-105 transition shadow-lg">Publier le commentaire</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endauth

                    <div class="space-y-6">
                        @forelse($commentsList as $comment)
                            <div class="flex gap-4 p-6 rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-50 dark:border-slate-800 shadow-sm transition-colors">
                                <img src="{{ $comment->user->photo ? asset('storage/'.$comment->user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=f8fafc&color=64748b' }}" class="w-10 h-10 rounded-full border border-slate-100">
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-2">
                                        <h4 class="text-sm font-black uppercase dark:text-white">{{ $comment->user->name }}</h4>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-base text-slate-600 dark:text-slate-400 italic leading-snug">"{{ $comment->content }}"</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-400 text-sm italic py-10">Soyez le premier à donner votre avis !</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </article>

        {{-- SIDEBAR --}}
        <aside class="space-y-8">
            {{-- Ton code de sidebar reste ici --}}
            @include('profile.partials.sidebar')
        </aside>
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
    .catch(err => console.error(err));
}
</script>

<style>
    .article-box img {
        max-height: 600px;
        width: 100%;
        object-fit: cover;
        margin: 3rem auto;
        border-radius: 2rem;
        display: block;
        shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }
    .article-box p { margin-bottom: 2rem; line-height: 1.8; }
</style>
@endsection
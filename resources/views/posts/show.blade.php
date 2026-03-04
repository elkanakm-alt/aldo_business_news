@extends('layouts.app')

@section('content')
@php 
    \Carbon\Carbon::setLocale('fr');
    $wordCount = str_word_count(strip_tags($post->content));
    $readingTime = ceil($wordCount / 200); 
    $commentsList = isset($comments) ? $comments : $post->comments()->whereNull('parent_id')->where('status', 'approved')->latest()->get();
@endphp

{{-- Changement du container pour qu'il ne bloque pas la largeur sur mobile --}}
<div class="w-full bg-white dark:bg-gray-950 min-h-screen">
    
    {{-- IMAGE PRINCIPALE : Pleine largeur sur mobile, arrondie sur PC --}}
    <div class="max-w-6xl mx-auto md:pt-6">
        <div class="relative h-64 md:h-[60vh] w-full overflow-hidden md:rounded-3xl shadow-lg">
            <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/default.jpg') }}" 
                 class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>
    </div>

    {{-- CORPS DE L'ARTICLE --}}
    <div class="max-w-6xl mx-auto px-0 md:px-6 py-6 md:py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            
            {{-- ZONE DE LECTURE : On retire les paddings excessifs sur mobile --}}
            <main class="w-full lg:w-2/3 px-4 md:px-0">
                <h1 class="text-2xl md:text-5xl font-black leading-tight mb-6 text-slate-900 dark:text-white italic tracking-tighter">
                    {{ $post->title }}
                </h1>

                {{-- META INFOS --}}
                <div class="flex items-center justify-between mb-10 pb-6 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <img src="{{ $post->user && $post->user->photo ? asset('storage/'.$post->user->photo) : 'https://ui-avatars.com/api/?background=10b981&color=fff&name='.urlencode($post->user->name ?? 'A') }}" 
                             class="w-11 h-11 rounded-full border-2 border-emerald-500 shadow-sm object-cover">
                        <div>
                            <p class="font-black text-slate-900 dark:text-white text-xs uppercase leading-none mb-1">{{ $post->user->name ?? 'Auteur' }}</p>
                            <p class="text-[9px] text-slate-400 uppercase font-bold tracking-widest">{{ $post->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold uppercase text-slate-500 bg-slate-50 dark:bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-100 dark:border-slate-700">⏱ {{ $readingTime }} min</span>
                        <button onclick="likePost({{ $post->id }})" class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 px-3 py-1.5 rounded-lg active:scale-125 transition shadow-sm font-bold text-[10px]">
                            ❤️ <span id="like-count-{{ $post->id }}">{{ $post->likes ?? 0 }}</span>
                        </button>
                    </div>
                </div>

                {{-- CONTENU : max-w-none pour utiliser toute la largeur allouée --}}
                <article class="article-box prose prose-slate dark:prose-invert max-w-none text-base md:text-xl leading-relaxed text-slate-700 dark:text-slate-300">
                    {!! $post->content !!}
                </article>

                {{-- SECTION COMMENTAIRES --}}
                <section class="mt-16 pt-10 border-t border-slate-100 dark:border-slate-800">
                    <h3 class="text-xl font-black uppercase italic mb-8 dark:text-white">Discussion ({{ $commentsList->count() }})</h3>
                    @auth
                        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-10">
                            @csrf
                            <div class="flex gap-4 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl">
                                <textarea name="content" rows="2" class="flex-1 bg-transparent border-none focus:ring-0 text-sm dark:text-white" placeholder="Votre avis..."></textarea>
                                <button type="submit" class="self-end px-4 py-2 bg-emerald-600 text-white text-[10px] font-black uppercase rounded-lg shadow-lg">Envoyer</button>
                            </div>
                        </form>
                    @endauth

                    <div class="space-y-6">
                        @foreach($commentsList as $comment)
                            <div class="flex gap-4">
                                <img src="{{ $comment->user->photo ? asset('storage/'.$comment->user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name) }}" class="w-8 h-8 rounded-full">
                                <div class="flex-1 bg-slate-50 dark:bg-slate-800/30 p-4 rounded-2xl">
                                    <h4 class="text-[10px] font-black uppercase dark:text-white">{{ $comment->user->name }}</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 italic leading-snug">"{{ $comment->content }}"</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </main>

            {{-- SIDEBAR : Se place en dessous sur mobile --}}
            <aside class="w-full lg:w-1/3 px-4 md:px-0 space-y-8">
                <div class="bg-slate-50 dark:bg-slate-900/50 p-6 rounded-3xl border border-slate-100 dark:border-slate-800">
                    <h3 class="text-xs font-black mb-6 uppercase italic flex items-center gap-2 dark:text-white">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span> À ne pas manquer
                    </h3>
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($popularPosts as $popular)
                            <a href="{{ route('post.show', $popular->slug) }}" class="flex items-center gap-4 group">
                                <div class="relative w-16 h-16 shrink-0 overflow-hidden rounded-xl shadow-sm">
                                    <img src="{{ $popular->image ? asset('storage/'.$popular->image) : asset('images/default.jpg') }}" class="w-full h-full object-cover transition group-hover:scale-110">
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-[11px] font-extrabold group-hover:text-emerald-500 transition line-clamp-2 uppercase leading-tight italic dark:text-slate-200">{{ $popular->title }}</h4>
                                    <span class="text-[8px] text-slate-400 font-bold uppercase mt-1 block">👁 {{ $popular->views }} vues</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
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
    /* Correction pour mobile : les images dans le contenu s'adaptent */
    .article-box img {
        max-width: 100%;
        height: auto;
        border-radius: 1rem;
        margin: 2rem auto;
        display: block;
    }
    .article-box p { margin-bottom: 1.5rem; }
    /* Supprime les marges horizontales par défaut de Tailwind Typography sur mobile */
    .prose { max-width: 100% !important; }
</style>
@endsection
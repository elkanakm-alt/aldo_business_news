@extends('layouts.app')

@section('content')
@php 
    \Carbon\Carbon::setLocale('fr');
    $wordCount = str_word_count(strip_tags($post->content));
    $readingTime = ceil($wordCount / 200); 
    $commentsList = isset($comments) ? $comments : $post->comments()->whereNull('parent_id')->where('status', 'approved')->latest()->get();
@endphp

<div class="container-main py-6 md:py-10">
    <div class="grid lg:grid-cols-3 gap-10">
        {{-- COLONNE LECTURE --}}
        <article class="lg:col-span-2">
            
            {{-- Image principale plus courte sur mobile --}}
            <div class="mb-6 px-2 md:px-0">
                <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/default.jpg') }}" 
                     class="w-full h-56 md:h-[50vh] object-cover rounded-3xl shadow-lg transition-transform duration-500 hover:scale-[1.01]">
            </div>

            <div class="px-4">
                <h1 class="text-2xl md:text-4xl font-extrabold leading-tight mb-4 text-slate-900 dark:text-white italic tracking-tight">
                    {{ $post->title }}
                </h1>

                <div class="flex flex-wrap items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <img src="{{ $post->user && $post->user->photo ? asset('storage/'.$post->user->photo) : 'https://ui-avatars.com/api/?background=10b981&color=fff&name='.urlencode($post->user->name ?? 'A') }}" 
                             class="w-10 h-10 rounded-full border-2 border-emerald-500 shadow-sm object-cover">
                        <div class="font-sans">
                            <p class="font-bold text-slate-900 dark:text-white text-xs uppercase leading-none mb-1">{{ $post->user->name ?? 'Auteur' }}</p>
                            <p class="text-[9px] text-slate-400 uppercase font-bold tracking-widest">{{ $post->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 text-[10px] font-bold uppercase text-slate-500">
                        <span class="bg-slate-50 dark:bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-100">⏱ {{ $readingTime }} min</span>
                        <button onclick="likePost({{ $post->id }})" class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 px-3 py-1.5 rounded-lg active:scale-125 transition shadow-sm">
                            ❤️ <span id="like-count-{{ $post->id }}">{{ $post->likes ?? 0 }}</span>
                        </button>
                    </div>
                </div>

                {{-- CONTENU : Largeur limitée (max-w-3xl) pour un confort de lecture optimal --}}
                <div class="article-box prose dark:prose-invert max-w-3xl mx-auto text-base md:text-lg leading-relaxed mb-16 text-slate-700 dark:text-slate-300 font-sans">
                    {!! $post->content !!}
                </div>

                {{-- SECTION DISCUSSION --}}
                <section class="mt-10 pt-10 border-t border-slate-100 dark:border-slate-800 max-w-3xl mx-auto">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black uppercase italic dark:text-white tracking-tighter">Discussion</h3>
                        <span class="text-[10px] font-bold px-3 py-1 bg-emerald-500/10 text-emerald-600 rounded-full uppercase">
                            {{ $commentsList->count() }} avis
                        </span>
                    </div>

                    @auth
                        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-10 bg-slate-50 dark:bg-slate-800/40 p-5 rounded-2xl border border-slate-100 dark:border-slate-800">
                            @csrf
                            <div class="flex gap-4">
                                <img src="{{ auth()->user()->photo ? asset('storage/'.auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=10b981&color=fff' }}" class="w-9 h-9 rounded-xl object-cover shadow-md">
                                <div class="flex-1">
                                    <textarea name="content" rows="2" class="w-full bg-white dark:bg-slate-900 border-none rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 dark:text-white transition-all" placeholder="Votre avis..."></textarea>
                                    <div class="flex justify-end mt-2">
                                        <button type="submit" class="px-5 py-2 bg-slate-900 dark:bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:scale-105 transition">Publier</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endauth

                    <div class="space-y-6">
                        @forelse($commentsList as $comment)
                            <div class="flex gap-4 p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                                <img src="{{ $comment->user->photo ? asset('storage/'.$comment->user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=f8fafc&color=64748b' }}" class="w-8 h-8 rounded-full border border-slate-100">
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-1">
                                        <h4 class="text-[11px] font-black uppercase dark:text-white">{{ $comment->user->name }}</h4>
                                        <span class="text-[9px] text-slate-400 font-bold uppercase">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 italic leading-snug">"{{ $comment->content }}"</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-400 text-xs italic py-6">Aucun commentaire pour le moment.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </article>

        {{-- SIDEBAR COMPACTE --}}
        <aside class="space-y-8">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
                <h3 class="text-sm font-black mb-5 uppercase italic flex items-center gap-2 dark:text-white">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Explorer
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($categories as $category)
                        <a href="{{ route('category.show', $category->slug) }}" class="px-3 py-1.5 bg-slate-50 dark:bg-slate-800 hover:bg-emerald-500 hover:text-white transition-all rounded-lg text-[9px] font-bold uppercase text-slate-500">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
                <h3 class="text-sm font-black mb-5 uppercase italic flex items-center gap-2 dark:text-white">
                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span> Populaire
                </h3>
                <div class="space-y-5">
                    @foreach($popularPosts as $popular)
                        <a href="{{ route('post.show', $popular->slug) }}" class="flex items-center gap-3 group">
                            <img src="{{ $popular->image ? asset('storage/'.$popular->image) : asset('images/default.jpg') }}" class="w-12 h-12 rounded-lg object-cover shadow-sm transition group-hover:scale-105">
                            <div class="flex-1">
                                <h4 class="text-[10px] font-bold group-hover:text-emerald-500 transition line-clamp-2 uppercase leading-tight italic dark:text-slate-300">{{ $popular->title }}</h4>
                                <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">👁 {{ $popular->views }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
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
    /* Images internes limitées en hauteur */
    .article-box img {
        max-height: 480px;
        width: auto;
        margin: 2.5rem auto;
        border-radius: 1.5rem;
        display: block;
    }
    .article-box p { margin-bottom: 1.8rem; line-height: 1.8; }
</style>
@endsection
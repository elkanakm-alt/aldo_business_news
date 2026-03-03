@extends('layouts.app')

@section('content')
@php 
    \Carbon\Carbon::setLocale('fr');
    $wordCount = str_word_count(strip_tags($post->content));
    $readingTime = ceil($wordCount / 200); 
    
    // SÉCURITÉ : Si le contrôleur a oublié $comments, on récupère les comms du post
    $commentsList = isset($comments) ? $comments : $post->comments()->whereNull('parent_id')->where('status', 'approved')->latest()->get();
@endphp

<div class="container-main py-8 md:py-12 px-4 md:px-0">
    <div class="grid lg:grid-cols-3 gap-10">
        {{-- COLONNE GAUCHE --}}
        <article class="lg:col-span-2">
            
            <div class="mb-8">
                <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/default.jpg') }}" 
                     class="w-full h-auto max-h-[70vh] object-cover rounded-3xl transition-transform duration-700 hover:scale-[1.01]">
            </div>

            <div class="md:px-4">
                <h1 class="text-2xl md:text-3xl font-bold leading-tight mb-4 text-slate-900 dark:text-white">{{ $post->title }}</h1>

                <div class="mb-8 pb-6 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3 mb-5">
                        <img src="{{ $post->user && $post->user->photo ? asset('storage/'.$post->user->photo) : 'https://ui-avatars.com/api/?background=10b981&color=fff&name='.urlencode($post->user->name ?? 'A') }}" 
                             class="w-10 h-10 rounded-full border-2 border-emerald-500 shadow-sm object-cover">
                        <div class="font-sans">
                            <p class="font-bold text-slate-900 dark:text-white text-sm uppercase leading-none mb-1">{{ $post->user->name ?? 'Auteur' }}</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">{{ $post->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3 text-[12px] font-bold uppercase text-slate-600 dark:text-slate-400 font-sans">
                        <span class="bg-slate-50 dark:bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-100">📝 {{ $wordCount }} mots</span>
                        <span class="bg-slate-50 dark:bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-100">⏱ {{ $readingTime }} min</span>
                        <span class="bg-slate-50 dark:bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-100">👁 {{ $post->views ?? 0 }} vues</span>
                        <button onclick="likePost({{ $post->id }})" class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 px-3 py-1.5 rounded-lg active:scale-125 transition-transform shadow-sm">
                            ❤️ <span id="like-count-{{ $post->id }}">{{ $post->likes ?? 0 }}</span>
                        </button>
                    </div>
                </div>

                <div class="article-box prose dark:prose-invert max-w-none text-base md:text-lg leading-relaxed mb-12 text-slate-700 dark:text-slate-300">
                    {!! $post->content !!}
                </div>

                {{-- SECTION COMMENTAIRES --}}
                <section class="mt-20 relative">
                    <div class="flex items-center gap-4 mb-12">
                        <h3 class="text-2xl font-black font-sans uppercase tracking-[-0.05em] italic text-slate-900 dark:text-white">
                            Espace Discussion
                        </h3>
                        <div class="h-[1px] flex-1 bg-gradient-to-r from-slate-200 via-slate-100 to-transparent dark:from-slate-700 dark:via-slate-800"></div>
                        <span class="px-4 py-1 rounded-full bg-emerald-500/10 text-emerald-600 text-[11px] font-black tracking-widest uppercase">
                            {{ isset($comments) ? $comments->total() : $post->comments->where('status', 'approved')->count() }} Avis
                        </span>
                    </div>

                    @auth
                        <div class="relative mb-16 p-[1px] rounded-[2rem] bg-gradient-to-br from-slate-100 to-transparent dark:from-slate-700 dark:to-transparent">
                            <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-6 shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-50 dark:border-slate-800">
                                <form action="{{ route('comments.store', $post->id) }}" method="POST">
                                    @csrf
                                    <div class="flex gap-4 mb-4">
                                        <img src="{{ auth()->user()->photo ? asset('storage/'.auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=10b981&color=fff' }}" class="w-10 h-10 rounded-2xl object-cover shadow-lg">
                                        <div class="flex-1">
                                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Partagez votre analyse</label>
                                            <textarea name="content" rows="3" class="w-full bg-slate-50 dark:bg-slate-800/50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-emerald-500/20 dark:text-white transition-all" placeholder="Écrivez quelque chose d'inspirant..."></textarea>
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-slate-900 to-slate-800 dark:from-emerald-600 dark:to-teal-600 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-xl hover:scale-105 transition-transform shadow-xl">
                                            Publier l'avis →
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="mb-16 p-8 rounded-[2rem] border-2 border-dashed border-slate-100 dark:border-slate-800 text-center">
                            <p class="text-sm font-sans text-slate-500">Rejoignez la communauté pour interagir. <a href="{{ route('login') }}" class="text-emerald-500 font-black uppercase tracking-widest ml-2 hover:underline">Connexion →</a></p>
                        </div>
                    @endauth

                    {{-- LISTE DES MESSAGES --}}
                    <div class="space-y-8 relative">
                        <div class="absolute left-5 top-0 bottom-0 w-[1px] bg-gradient-to-b from-emerald-500/20 via-slate-100 to-transparent dark:via-slate-800"></div>

                        @forelse($commentsList as $comment)
                            <div class="relative flex gap-6 group">
                                <div class="relative z-10">
                                    <div class="w-10 h-10 rounded-2xl overflow-hidden ring-4 ring-white dark:ring-slate-900 shadow-xl group-hover:scale-110 transition-transform">
                                        <img src="{{ $comment->user->photo ? asset('storage/'.$comment->user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=f8fafc&color=64748b' }}" class="w-full h-full object-cover">
                                    </div>
                                </div>
                                <div class="flex-1 pb-8 border-b border-slate-50 dark:border-slate-800/50">
                                    <div class="flex justify-between items-center mb-3">
                                        <div>
                                            <h4 class="text-[12px] font-black uppercase tracking-tight text-slate-900 dark:text-white">{{ $comment->user->name }}</h4>
                                            <span class="text-[9px] font-bold text-emerald-500/60 uppercase tracking-widest">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <p class="text-[15px] text-slate-600 dark:text-slate-400 leading-relaxed font-sans italic">
                                        "{{ $comment->content }}"
                                    </p>

                                    @foreach($comment->replies as $reply)
                                        <div class="mt-6 flex gap-4 items-start bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border-l-4 border-emerald-500">
                                            <img src="{{ $reply->user->photo ? asset('storage/'.$reply->user->photo) : 'https://ui-avatars.com/api/?name=Admin&background=10b981&color=fff' }}" class="w-8 h-8 rounded-xl shadow-md object-cover">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h5 class="text-[11px] font-black uppercase text-emerald-600">{{ $reply->user->name }}</h5>
                                                    <span class="px-2 py-0.5 bg-emerald-500 text-white text-[8px] font-bold rounded-md uppercase">Staff</span>
                                                </div>
                                                <p class="text-sm text-slate-700 dark:text-slate-300 font-sans">{{ $reply->content }}</p>
                                                <p class="text-[8px] text-slate-400 font-bold mt-2 uppercase">{{ $reply->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-400 italic text-sm text-center py-10">Aucun commentaire pour le moment.</p>
                        @endforelse
                    </div>

                    {{-- BARRE DE PAGINATION ADAPTÉE (Fix Mobile) --}}
                    @if(isset($comments) && method_exists($comments, 'links') && $comments->hasPages())
                        <div class="mt-12 mb-10 pagination-custom flex justify-center overflow-x-auto">
                            {{ $comments->links() }}
                        </div>
                    @endif
                </section>

                {{-- SUGGESTIONS --}}
                @if(isset($relatedPosts) && $relatedPosts->count() > 0)
                <div class="mt-20 border-t border-slate-100 dark:border-slate-800 pt-10">
                    <div class="flex flex-col mb-10">
                        <span class="text-emerald-500 font-black text-[10px] uppercase tracking-[0.3em] mb-2">Suggestion de lecture</span>
                        <div class="flex items-center">
                            <h3 class="text-2xl font-black font-sans text-slate-900 dark:text-white uppercase tracking-tighter italic">Dans la même lignée</h3>
                            <div class="ml-4 h-[2px] flex-1 bg-gradient-to-r from-emerald-500 to-transparent opacity-30"></div>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-3 gap-8">
                        @foreach($relatedPosts as $related)
                            <a href="{{ route('post.show', $related->slug) }}" class="group block">
                                <div class="relative overflow-hidden rounded-2xl mb-4 h-40 shadow-sm bg-slate-100">
                                    <img src="{{ $related->image ? asset('storage/'.$related->image) : asset('images/default.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                </div>
                                <h4 class="font-bold text-sm line-clamp-2 group-hover:text-emerald-500 transition-colors font-sans leading-tight italic">{{ $related->title }}</h4>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </article>

        {{-- SIDEBAR --}}
        <aside class="space-y-10">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
                <h3 class="text-lg font-bold mb-5 flex items-center gap-2 uppercase tracking-tighter italic">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Catégories
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($categories as $category)
                        <a href="{{ route('category.show', $category->slug) }}" class="px-3 py-2 bg-slate-50 dark:bg-slate-800 hover:bg-emerald-500 hover:text-white transition-all rounded-xl text-[10px] font-black uppercase text-slate-500 border border-slate-100 dark:border-slate-700">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
                <h3 class="text-lg font-bold mb-5 flex items-center gap-2 uppercase tracking-tighter italic">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span> À ne pas manquer
                </h3>
                <div class="space-y-5">
                    @foreach($popularPosts as $popular)
                        <a href="{{ route('post.show', $popular->slug) }}" class="flex items-center gap-4 group">
                            <img src="{{ $popular->image ? asset('storage/'.$popular->image) : asset('images/default.jpg') }}" class="w-14 h-14 rounded-xl object-cover shadow-sm transition group-hover:scale-105">
                            <div>
                                <h4 class="text-[11px] font-bold group-hover:text-emerald-500 transition line-clamp-2 leading-tight uppercase font-sans italic">{{ $popular->title }}</h4>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">👁 {{ $popular->views }} lectures</span>
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
    /* Correction de la pagination en responsive */
    .pagination-custom nav {
        display: flex !important;
        justify-content: center;
        width: 100%;
    }

    /* Cache le texte "Showing 1 to 6..." pour gagner de la place sur mobile */
    .pagination-custom nav div:first-child p {
        display: none !important;
    }

    /* Force l'affichage des boutons sur mobile */
    .pagination-custom nav div:last-child {
        display: flex !important;
        box-shadow: none !important;
    }

    .pagination-custom nav svg { width: 1.25rem; height: 1.25rem; }

    /* Style des liens */
    .pagination-custom a, .pagination-custom span {
        border-radius: 12px !important;
        margin: 0 3px !important;
        border: none !important;
        font-size: 13px !important;
        font-weight: 800 !important;
    }

    @media (min-width: 768px) { 
        .pagination-custom nav div:first-child { display: flex !important; } 
    }
</style>
@endsection
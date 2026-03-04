@extends('layouts.app')

@section('content')
@php 
    \Carbon\Carbon::setLocale('fr');
    $wordCount = str_word_count(strip_tags($post->content));
    $readingTime = ceil($wordCount / 200); 
    
    // Pagination des commentaires (4 par page)
    $commentsList = $post->comments()
        ->whereNull('parent_id')
        ->where('status', 'approved')
        ->latest()
        ->paginate(4, ['*'], 'comments_page');

    // On suppose que les articles liés sont aussi paginés si nécessaire
    $relatedPostsPaginated = $relatedPosts instanceof \Illuminate\Pagination\LengthAwarePaginator 
        ? $relatedPosts 
        : $relatedPosts->take(4); 
@endphp

<div class="max-w-[1550px] mx-auto bg-white dark:bg-slate-950 min-h-screen px-4 md:px-10">
    
    <div class="flex flex-col lg:flex-row gap-8 pt-6">
        
        {{-- COLONNE GAUCHE --}}
        <div class="flex-1 w-full">
            
            {{-- 1. PHOTO EN VEDETTE --}}
            <div class="w-full mb-6">
                <div class="w-full h-[300px] md:h-[450px] rounded-[3rem] md:rounded-[4rem] overflow-hidden shadow-2xl bg-slate-900 border border-slate-100 dark:border-slate-800 flex items-center justify-center">
                    <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/default.jpg') }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $post->title }}">
                </div>
            </div>

            {{-- 2. TITRE SOUS PHOTO --}}
            <div class="mb-6 px-4">
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white italic tracking-tighter leading-tight uppercase">
                    {{ $post->title }}
                </h1>
            </div>

            {{-- 3. STATISTIQUES --}}
            <div class="mb-8 px-4">
                <div class="flex items-center gap-3 overflow-x-auto pb-2 no-scrollbar">
                    <div class="flex items-center shrink-0 gap-3 bg-slate-50 dark:bg-slate-900 px-4 py-2 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                        <img src="{{ $post->user && $post->user->photo ? asset('storage/'.$post->user->photo) : 'https://ui-avatars.com/api/?background=10b981&color=fff&name='.urlencode($post->user->name ?? 'A') }}" 
                             class="w-6 h-6 rounded-full border border-emerald-500 object-cover">
                        <span class="font-black text-slate-900 dark:text-white text-[9px] uppercase tracking-tighter">{{ $post->user->name ?? 'Admin' }}</span>
                    </div>

                    <div class="flex items-center shrink-0 gap-4 text-[9px] font-black uppercase tracking-widest text-slate-500 bg-slate-50 dark:bg-slate-900 px-5 py-3 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                        <span class="text-slate-400 font-bold tracking-normal">{{ $post->created_at->translatedFormat('d M Y') }}</span>
                        <span class="text-emerald-500 font-black italic">📖 {{ $wordCount }} MOTS</span>
                        <button onclick="likePost({{ $post->id }})" class="text-rose-500 flex items-center gap-1 active:scale-125 transition">
                            ❤️ <span id="like-count-{{ $post->id }}">{{ $post->likes ?? 0 }}</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- 4. LE CADRE "BARRE" (CONTENU) --}}
            <div class="relative px-6 md:px-16 py-12 md:py-20 bg-white dark:bg-slate-900/40 shadow-[0_30px_70px_-15px_rgba(0,0,0,0.15)] dark:shadow-[0_30px_70px_-15px_rgba(0,0,0,0.5)] rounded-[3rem] md:rounded-[4rem] border border-slate-100/50 dark:border-slate-800/50">
                <div class="hidden md:block absolute top-0 left-0 w-24 h-24 border-t-[6px] border-l-[6px] border-emerald-500 rounded-tl-[4rem]"></div>
                <div class="article-content prose dark:prose-invert max-w-none text-base leading-[1.8] text-slate-700 dark:text-slate-300 font-sans">
                    {!! $post->content !!}
                </div>
            </div>

            {{-- 5. DISCUSSION AVEC PAGINATION --}}
            <section class="mt-20 px-4" id="comments-section">
                <h3 class="text-2xl font-black uppercase italic dark:text-white tracking-tighter flex items-center gap-3 mb-10">
                    <span class="w-10 h-[4px] bg-emerald-500"></span> Discussion
                </h3>

                @auth
                    <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-14">
                        @csrf
                        <div class="bg-slate-50 dark:bg-slate-900/50 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-inner">
                            <textarea name="content" rows="2" class="w-full bg-transparent border-none focus:ring-0 text-sm dark:text-white" placeholder="Ajouter un avis..."></textarea>
                            <div class="flex justify-end mt-2">
                                <button type="submit" class="px-8 py-2.5 bg-emerald-600 text-white text-[10px] font-black uppercase rounded-xl">Publier →</button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="mb-14 p-10 rounded-[3rem] bg-slate-50/50 dark:bg-slate-900/30 border-2 border-dashed border-slate-200 dark:border-slate-800 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6 italic">Connectez-vous pour discuter</p>
                        <a href="{{ route('login') }}" class="px-8 py-3 bg-emerald-600 text-white text-[10px] font-black uppercase rounded-full shadow-lg inline-block">Connexion</a>
                    </div>
                @endauth

                <div class="space-y-4">
                    @forelse($commentsList as $comment)
                        <div class="flex flex-col gap-1">
                            <div class="flex gap-3 items-start">
                                <img src="{{ $comment->user->photo ? asset('storage/'.$comment->user->photo) : 'https://ui-avatars.com/api/?background=f1f5f9&color=10b981&name='.urlencode($comment->user->name) }}" class="w-9 h-9 rounded-full object-cover shrink-0 border border-white dark:border-slate-800 shadow-sm">
                                <div class="flex-1 bg-slate-50 dark:bg-slate-900/40 p-5 rounded-[1.8rem] rounded-tl-none border border-slate-50 dark:border-slate-800/50">
                                    <div class="flex justify-between items-center mb-1 text-[9px] font-black uppercase tracking-tighter">
                                        <span class="dark:text-white font-bold">{{ $comment->user->name }}</span>
                                        <span class="text-slate-400 italic lowercase">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 font-sans italic">"{{ $comment->content }}"</p>
                                </div>
                            </div>
                            @if($comment->replies)
                                @foreach($comment->replies as $reply)
                                    <div class="flex gap-3 items-start ml-12">
                                        <img src="{{ $reply->user->photo ? asset('storage/'.$reply->user->photo) : 'https://ui-avatars.com/api/?background=10b981&color=fff&name=S' }}" class="w-7 h-7 rounded-full object-cover">
                                        <div class="flex-1 bg-emerald-50/40 dark:bg-emerald-900/10 p-4 rounded-[1.5rem] rounded-tl-none border-l-4 border-emerald-500">
                                            <h5 class="text-[9px] font-black uppercase text-emerald-600 tracking-tight">{{ $reply->user->name }} <span class="ml-1 px-1 py-0.5 bg-emerald-500 text-white text-[6px] rounded">Staff</span></h5>
                                            <p class="text-sm text-slate-700 dark:text-slate-300 font-sans leading-tight">{{ $reply->content }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @empty
                        <p class="text-center text-slate-400 text-[9px] uppercase font-black italic">Aucun avis.</p>
                    @endforelse
                </div>

                {{-- PAGINATION COMMENTAIRES --}}
                <div class="mt-10 mb-20 custom-pagination">
                    {{ $commentsList->appends(['comments_page' => $commentsList->currentPage()])->links() }}
                </div>
            </section>

            {{-- 6. ARTICLES LIÉS --}}
            @if($relatedPosts->count() > 0)
            <div class="mt-10 mb-24 border-t border-slate-100 dark:border-slate-800 pt-16 px-4">
                <h3 class="text-xl font-black uppercase italic mb-8 dark:text-white tracking-tighter flex items-center gap-3">
                    <span class="w-10 h-[4px] bg-slate-200 dark:bg-slate-700"></span> À découvrir aussi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($relatedPostsPaginated as $related)
                    <a href="{{ route('posts.show', $related->slug) }}" class="group flex items-center gap-4 bg-slate-50 dark:bg-slate-900/30 p-4 rounded-[2rem] border border-transparent hover:border-emerald-500/30 transition-all">
                        <img src="{{ $related->image ? asset('storage/'.$related->image) : asset('images/default.jpg') }}" class="w-16 h-16 rounded-2xl object-cover shadow-sm">
                        <h4 class="text-[11px] font-black uppercase italic leading-tight dark:text-white group-hover:text-emerald-500 transition line-clamp-2">{{ $related->title }}</h4>
                    </a>
                    @endforeach
                </div>
                
                {{-- SI ARTICLES LIÉS SONT NOMBREUX, AJOUTER PAGINATION ICI --}}
                @if($relatedPosts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-8 custom-pagination">
                        {{ $relatedPosts->links() }}
                    </div>
                @endif
            </div>
            @endif

        </div>

        {{-- SIDEBAR --}}
        <aside class="w-full lg:w-[350px] shrink-0">
            <div class="lg:sticky lg:top-6 space-y-6">
                @include('profile.partials.sidebar')
            </div>
        </aside>

    </div>
</div>

<style>
    /* Style minimaliste pour la pagination */
    .custom-pagination nav { display: flex; justify-content: center; gap: 10px; }
    .custom-pagination svg { width: 20px; }
    .custom-pagination span, .custom-pagination a { 
        padding: 8px 16px; border-radius: 12px; background: #f8fafc; color: #64748b; font-size: 10px; font-weight: 900; text-transform: uppercase;
    }
    .dark .custom-pagination span, .dark .custom-pagination a { background: #0f172a; color: #94a3b8; }
    .custom-pagination .active { background: #10b981 !important; color: white !important; }
    
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .article-content img { width: 100%; height: auto; border-radius: 2rem; margin: 2rem 0; }
</style>

<script>
function likePost(postId) {
    fetch('/post/' + postId + '/like', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    }).then(res => res.json()).then(data => { if(data.likes !== undefined) document.getElementById('like-count-' + postId).innerText = data.likes; })
}
</script>
@endsection
@extends('layouts.app')

@section('content')
@php 
    \Carbon\Carbon::setLocale('fr');
    $wordCount = str_word_count(strip_tags($post->content));
    $readingTime = ceil($wordCount / 200); 
    
    $commentsList = $post->comments()
        ->whereNull('parent_id')
        ->where('status', 'approved')
        ->latest()
        ->paginate(4, ['*'], 'comments_page');

    $relatedPostsPaginated = $relatedPosts instanceof \Illuminate\Pagination\LengthAwarePaginator 
        ? $relatedPosts 
        : $relatedPosts->take(4); 
@endphp

<div class="max-w-[1550px] mx-auto bg-white dark:bg-slate-950 min-h-screen px-4 md:px-10">
    
    {{-- AJOUT DE items-start POUR FIXER LA SIDEBAR --}}
    <div class="flex flex-col lg:flex-row items-start gap-8 pt-6">
        
        {{-- COLONNE GAUCHE (70% de largeur sur PC) --}}
        <div class="w-full lg:w-[70%] flex-1">
            
            {{-- 1. PHOTO EN VEDETTE --}}
            <div class="w-full mb-6">
                <div class="w-full h-[300px] md:h-[450px] rounded-[3rem] md:rounded-[4rem] overflow-hidden shadow-2xl bg-slate-900 border border-slate-100 dark:border-slate-800 flex items-center justify-center">
                    {{-- Modification ici pour accepter les URL Cloudinary directes --}}
                    <img src="{{ Str::startsWith($post->image, 'http') ? $post->image : asset('storage/'.$post->image) }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $post->title }}">
                </div>
            </div>

            {{-- 2. TITRE --}}
            <div class="mb-6 px-4">
                <h1 class="text-2xl md:text-4xl font-black text-slate-900 dark:text-white italic tracking-tighter leading-tight uppercase">
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

            {{-- 4. CONTENU --}}
            <div class="relative px-6 md:px-16 py-12 md:py-20 bg-white dark:bg-slate-900/40 shadow-[0_30px_70px_-15px_rgba(0,0,0,0.15)] rounded-[3rem] md:rounded-[4rem] border border-slate-100/50 dark:border-slate-800/50">
                <div class="hidden md:block absolute top-0 left-0 w-24 h-24 border-t-[6px] border-l-[6px] border-emerald-500 rounded-tl-[4rem]"></div>
                <div class="article-content prose dark:prose-invert max-w-none text-base leading-[1.8] text-slate-700 dark:text-slate-300 font-sans">
                    {!! $post->content !!}
                </div>
            </div>

            {{-- 5. DISCUSSION --}}
            <section class="mt-20 px-4" id="discussion">
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
                @endauth

                <div class="space-y-4">
                    @foreach($commentsList as $comment)
                        <div class="flex flex-col gap-1">
                            <div class="flex gap-3 items-start">
                                <img src="https://ui-avatars.com/api/?background=f1f5f9&color=10b981&name={{ urlencode($comment->user->name) }}" class="w-9 h-9 rounded-full object-cover shadow-sm">
                                <div class="flex-1 bg-slate-50 dark:bg-slate-900/40 p-5 rounded-[1.8rem] rounded-tl-none border border-slate-50 dark:border-slate-800/50">
                                    <div class="flex justify-between items-center mb-1 text-[9px] font-black uppercase tracking-tighter">
                                        <span class="dark:text-white font-bold">{{ $comment->user->name }}</span>
                                        <span class="text-slate-400 italic">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 font-sans italic">"{{ $comment->content }}"</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10 mb-20">
                    {{ $commentsList->fragment('discussion')->links() }}
                </div>
            </section>
        </div>

        {{-- SIDEBAR DROITE (30% de largeur sur PC) --}}
        <aside class="w-full lg:w-[30%] lg:sticky lg:top-10">
            <div class="space-y-6">
                @include('profile.partials.sidebar')
            </div>
        </aside>

    </div>
</div>
@endsection
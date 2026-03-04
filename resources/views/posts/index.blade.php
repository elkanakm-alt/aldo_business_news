@extends('layouts.app')

@section('content')
@php 
    use Illuminate\Support\Str; 
    \Carbon\Carbon::setLocale('fr');
@endphp

<div class="max-w-[1450px] mx-auto px-4 md:px-8 py-6 md:py-10">
    
    {{-- SECTION VEDETTE --}}
    @isset($featuredPost)
        <section class="relative rounded-[2.5rem] overflow-hidden group shadow-2xl mb-12">
            <img src="{{ Str::startsWith($featuredPost->image, 'http') ? $featuredPost->image : asset('storage/'.$featuredPost->image) }}" 
                 class="w-full h-[40vh] md:h-[60vh] object-cover transition duration-1000 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-8 md:p-12 text-white max-w-3xl">
                <h1 class="text-2xl md:text-5xl font-black mb-6 italic tracking-tighter uppercase">{{ $featuredPost->title }}</h1>
                <a href="{{ route('post.show', $featuredPost->slug) }}" class="inline-block px-8 py-3 rounded-full bg-white text-black text-xs font-black uppercase tracking-widest transition hover:bg-emerald-500 hover:text-white shadow-xl">
                    Lire l'article complet →
                </a>
            </div>
        </section>
    @endisset

    {{-- GRILLE PRINCIPALE AVEC SIDEBAR BIEN ALIGNÉE --}}
    <div class="flex flex-col lg:flex-row items-start gap-10">
        
        {{-- ARTICLES --}}
        <div class="w-full lg:w-[70%] space-y-10">
            <div class="grid md:grid-cols-2 gap-8">
                @foreach($posts as $post)
                    <article class="group bg-white dark:bg-slate-900 rounded-[2rem] overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 transition-all hover:shadow-2xl">
                        <div class="h-60 overflow-hidden relative"> 
                            <img src="{{ Str::startsWith($post->image, 'http') ? $post->image : asset('storage/'.$post->image) }}" 
                                 class="w-full h-full object-cover transition group-hover:scale-110">
                        </div>
                        <div class="p-8">
                            <h2 class="text-xl font-black mb-3 line-clamp-2 dark:text-white italic tracking-tight group-hover:text-emerald-600 transition-colors uppercase">
                                {{ $post->title }}
                            </h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 line-clamp-2 font-sans">
                                {{ Str::limit(strip_tags($post->content), 100) }}
                            </p>
                            <a href="{{ route('post.show', $post->slug) }}" class="text-[11px] font-black uppercase text-emerald-600 flex items-center gap-2">
                                Continuer <span class="group-hover:translate-x-1 transition-transform">→</span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        </div>

        {{-- SIDEBAR --}}
        <aside class="w-full lg:w-[30%] lg:sticky lg:top-10">
            @include('profile.partials.sidebar')
        </aside>
    </div>
</div>
@endsection
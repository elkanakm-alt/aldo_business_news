@extends('layouts.app')

@section('content')
@php 
    use Illuminate\Support\Str; 
    \Carbon\Carbon::setLocale('fr');
@endphp

{{-- Container élargi pour occuper l'espace normalement --}}
<div class="max-w-[1450px] mx-auto px-4 md:px-8 py-6 md:py-10">
    
    {{-- SECTION VEDETTE : Plus large et immersive --}}
    @isset($featuredPost)
        <section class="relative rounded-[2.5rem] overflow-hidden group shadow-2xl mb-12 mx-0">
            <img src="{{ $featuredPost->image ? asset('storage/'.$featuredPost->image) : asset('images/default.jpg') }}" 
                 class="w-full h-[40vh] md:h-[60vh] object-cover transition duration-1000 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-8 md:p-12 text-white max-w-3xl">
                <div class="inline-block p-[1px] rounded-full bg-gradient-to-r from-orange-500 to-emerald-500 mb-4 shadow-lg">
                    <span class="block px-4 py-1 rounded-full bg-black/40 backdrop-blur-md text-[10px] font-black uppercase tracking-[0.2em] font-sans">
                        🔥 À LA UNE
                    </span>
                </div>
                <h1 class="text-2xl md:text-5xl font-black mb-6 leading-tight italic tracking-tighter">{{ $featuredPost->title }}</h1>
                <a href="{{ route('post.show', $featuredPost->slug) }}" class="inline-block px-8 py-3 rounded-full bg-white text-black text-xs font-black uppercase tracking-widest transition hover:bg-emerald-500 hover:text-white transform hover:scale-105 shadow-xl">
                    Lire l'article complet →
                </a>
            </div>
        </section>
    @endisset

    {{-- GRILLE PRINCIPALE --}}
    <div class="grid lg:grid-cols-3 gap-10">
        
        {{-- COLONNE DES ARTICLES (2/3 de l'espace) --}}
        <div class="lg:col-span-2 space-y-10">
            <div class="grid md:grid-cols-2 gap-8">
                @foreach($posts as $post)
                    @php 
                        $wordCount = str_word_count(strip_tags($post->content));
                        $readingTime = ceil($wordCount / 200); 
                    @endphp
                    <article class="group flex flex-col h-full bg-white dark:bg-slate-900 rounded-[2rem] overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-500 hover:shadow-2xl hover:-translate-y-1">
                        {{-- Image de la carte --}}
                        <div class="overflow-hidden h-52 md:h-60 relative"> 
                            <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/default.jpg') }}" 
                                 class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                            <div class="absolute top-4 left-4">
                                <span class="bg-black/30 backdrop-blur-md text-white text-[9px] font-bold px-3 py-1 rounded-full uppercase">
                                    {{ $post->category->name ?? 'Actu' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6 md:p-8 flex-1 flex flex-col">
                            <h2 class="text-xl font-black mb-3 line-clamp-2 dark:text-white leading-tight italic tracking-tight group-hover:text-emerald-600 transition-colors">
                                {{ $post->title }}
                            </h2>
                            
                            <div class="flex items-center gap-3 mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                <img src="{{ $post->user && $post->user->photo ? asset('storage/'.$post->user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name ?? 'A') }}" 
                                     class="w-7 h-7 rounded-full object-cover border border-emerald-100">
                                <span>{{ $post->user->name ?? 'Admin' }}</span>
                                <span class="text-slate-200 dark:text-slate-700">•</span>
                                <span>{{ $post->created_at->translatedFormat('d M Y') }}</span>
                            </div>

                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 line-clamp-3 flex-1 font-sans leading-relaxed">
                                {{ Str::limit(strip_tags($post->content), 120) }}
                            </p>
                            
                            <div class="flex justify-between items-center pt-5 border-t border-slate-50 dark:border-slate-800">
                                <a href="{{ route('post.show', $post->slug) }}" class="text-[11px] font-black uppercase tracking-widest text-emerald-600 hover:emerald-400 transition flex items-center gap-2">
                                    Continuer <span class="group-hover:translate-x-1 transition-transform">→</span>
                                </a>
                                <div class="flex items-center gap-4 text-[10px] font-bold text-slate-400">
                                    <span class="flex items-center gap-1">👁 {{ $post->views ?? 0 }}</span>
                                    <button onclick="likePost({{ $post->id }})" class="flex items-center gap-1 hover:text-rose-500 transition active:scale-125">
                                        ❤️ <span id="like-count-{{ $post->id }}">{{ $post->likes ?? 0 }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        </div>

        {{-- SIDEBAR (1/3 de l'espace) --}}
        <aside class="space-y-8">
            <div class="sticky top-10">
                @include('profile.partials.sidebar')
            </div>
        </aside>
    </div>
</div>

<script>
function likePost(postId) {
    fetch('/post/' + postId + '/like', {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': '{{ csrf_token() }}', 
            'Accept': 'application/json', 
            'X-Requested-With': 'XMLHttpRequest' 
        }
    })
    .then(res => res.json())
    .then(data => { 
        if(data.likes !== undefined) {
            document.getElementById('like-count-' + postId).innerText = data.likes; 
        }
    })
    .catch(err => console.error('Erreur Like:', err));
}
</script>

<style>
    /* Pagination personnalisée pour correspondre au design */
    .pagination { @apply flex justify-center gap-2; }
    .page-item.active .page-link { @apply bg-emerald-600 border-emerald-600 text-white rounded-xl; }
    .page-link { @apply rounded-xl border-none bg-white dark:bg-slate-900 dark:text-white shadow-sm font-bold; }
</style>
@endsection
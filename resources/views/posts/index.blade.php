@extends('layouts.app')

@section('content')
@php 
    use Illuminate\Support\Str; 
    \Carbon\Carbon::setLocale('fr');
@endphp

<div class="container-main py-6 md:py-10">
    {{-- SECTION VEDETTE --}}
    @isset($featuredPost)
        <section class="relative rounded-3xl overflow-hidden group shadow-xl mb-10 mx-2 md:mx-0">
            <img src="{{ $featuredPost->image ? asset('storage/'.$featuredPost->image) : asset('images/default.jpg') }}" 
                 class="w-full h-[35vh] md:h-[50vh] object-cover transition duration-700 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-6 md:p-8 text-white max-w-2xl">
                <div class="inline-block p-[1px] rounded-full bg-gradient-to-r from-orange-500 to-emerald-500 mb-3 shadow-lg">
                    <span class="block px-3 py-1 rounded-full bg-black/40 backdrop-blur-md text-[9px] font-bold uppercase tracking-widest font-sans">
                        🔥 À LA UNE
                    </span>
                </div>
                <h1 class="text-xl md:text-3xl font-bold mb-3 leading-tight">{{ $featuredPost->title }}</h1>
                <a href="{{ route('post.show', $featuredPost->slug) }}" class="inline-block px-6 py-2 rounded-full bg-white text-black text-[10px] font-black uppercase tracking-widest transition hover:bg-emerald-500 hover:text-white transform hover:scale-105">
                    Lire l'article →
                </a>
            </div>
        </section>
    @endisset

    <div class="grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div class="grid md:grid-cols-2 gap-6 px-4 md:px-0">
                @foreach($posts as $post)
                    @php 
                        $wordCount = str_word_count(strip_tags($post->content));
                        $readingTime = ceil($wordCount / 200); 
                    @endphp
                    <article class="card group flex flex-col h-full bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 transition-all hover:shadow-md">
                        <div class="overflow-hidden h-44 md:h-48"> 
                            <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/default.jpg') }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        </div>
                        
                        <div class="p-5 flex-1 flex flex-col">
                            <h2 class="text-base font-bold mb-2 line-clamp-2 dark:text-white leading-snug">{{ $post->title }}</h2>
                            
                            <div class="flex justify-between items-center mb-3 text-[10px] font-bold uppercase text-slate-400">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $post->user && $post->user->photo ? asset('storage/'.$post->user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name ?? 'A') }}" class="w-6 h-6 rounded-full object-cover border border-emerald-100">
                                    <span class="text-slate-500">{{ $post->user->name ?? 'Admin' }}</span>
                                </div>
                                <span>{{ $post->created_at->translatedFormat('d M Y') }}</span>
                            </div>

                            <p class="text-xs text-slate-500 mb-4 line-clamp-2 flex-1 font-sans leading-relaxed">{{ Str::limit(strip_tags($post->content), 90) }}</p>
                            
                            <div class="flex justify-between items-center pt-3 border-t border-slate-50 dark:border-slate-800">
                                <a href="{{ route('post.show', $post->slug) }}" class="text-[10px] font-black uppercase tracking-widest text-emerald-600 hover:text-emerald-400 transition">
                                    Lire la suite →
                                </a>
                                <div class="flex items-center gap-3 text-[10px] font-bold text-slate-400">
                                    <span>👁 {{ $post->views ?? 0 }}</span>
                                    <button onclick="likePost({{ $post->id }})" class="hover:text-emerald-500 transition active:scale-125">
                                        ❤️ <span id="like-count-{{ $post->id }}">{{ $post->likes ?? 0 }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-8 px-4 md:px-0">{{ $posts->links() }}</div>
        </div>

        <aside class="px-4 md:px-0">
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
@endsection
@extends('layouts.app')

@section('content')
@php 
    use Illuminate\Support\Str; 
    // Force la langue en français pour Carbon sur cette page
    \Carbon\Carbon::setLocale('fr');
@endphp

<div class="container-main py-8 md:py-16">
    {{-- SECTION VEDETTE --}}
    @isset($featuredPost)
        <section class="relative rounded-3xl overflow-hidden group shadow-2xl mb-12 mx-2 md:mx-0">
            <img src="{{ $featuredPost->image ? asset('storage/'.$featuredPost->image) : asset('images/default.jpg') }}" class="w-full h-[45vh] md:h-[60vh] object-cover transition duration-700 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-6 md:p-10 text-white max-w-2xl">
                <div class="inline-block p-[1.5px] rounded-full bg-gradient-to-r from-orange-500 to-emerald-500 mb-4 shadow-lg">
                    <span class="block px-4 py-1 rounded-full bg-black/40 backdrop-blur-md text-[10px] font-bold uppercase tracking-widest font-sans">
                        🔥 Article en vedette
                    </span>
                </div>
                <h1 class="text-2xl md:text-4xl font-bold mb-4">{{ $featuredPost->title }}</h1>
                <a href="{{ route('post.show', $featuredPost->slug) }}" class="px-6 py-2 rounded-full bg-gradient-to-r from-blue-500 to-emerald-500 text-white text-[11px] font-black uppercase tracking-widest transition duration-300 hover:shadow-lg hover:brightness-110 group/btn">
                    Lire l'article →
                </a>
            </div>
        </section>
    @endisset

    <div class="grid lg:grid-cols-3 gap-8 md:gap-12">
        <div class="lg:col-span-2 space-y-12">
            <div class="grid md:grid-cols-2 gap-8 px-4 md:px-0">
                @foreach($posts as $post)
                    @php 
                        $wordCount = str_word_count(strip_tags($post->content));
                        $readingTime = ceil($wordCount / 200); 
                    @endphp
                    <article class="card group flex flex-col h-full bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl">
                        <div class="overflow-hidden h-56">
                            <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/default.jpg') }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        </div>
                        
                        <div class="p-6 flex-1 flex flex-col">
                            <h2 class="text-lg font-bold mb-3 line-clamp-2">{{ $post->title }}</h2>
                            
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $post->user && $post->user->photo ? asset('storage/'.$post->user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name ?? 'A') }}" class="w-7 h-7 rounded-full object-cover border border-emerald-100">
                                    <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 font-sans uppercase">{{ $post->user->name ?? 'Admin' }}</span>
                                </div>
                                {{-- Date en français --}}
                                <span class="text-[10px] font-bold text-slate-400 font-sans uppercase tracking-tighter">{{ $post->created_at->translatedFormat('d M Y') }}</span>
                            </div>

                            <div class="flex gap-3 text-[10px] font-bold uppercase tracking-widest text-emerald-600 mb-4 font-sans">
                                <span>📝 {{ $wordCount }} mots</span>
                                <span>⏱ {{ $readingTime }} min</span>
                            </div>

                            <p class="text-sm text-gray-500 mb-6 flex-1 line-clamp-3 font-sans">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                            
                            <div class="flex justify-between items-center pt-4 border-t border-slate-50 dark:border-slate-800">
                                <a href="{{ route('post.show', $post->slug) }}" class="px-5 py-2 rounded-full bg-gradient-to-r from-blue-500 to-emerald-500 text-white text-[10px] font-black uppercase tracking-widest transition duration-300 hover:shadow-lg hover:brightness-110 group/btn">
                                    Lire l'article →
                                </a>
                                <div class="flex items-center gap-3">
                                    <span class="text-slate-400 text-[11px] font-bold font-sans">👁 {{ $post->views ?? 0 }}</span>
                                    <button type="button" onclick="likePost({{ $post->id }})" class="flex items-center gap-1 text-emerald-500 font-bold hover:scale-125 transition">
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
        <aside class="px-4 md:px-0">@include('profile.partials.sidebar')</aside>
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
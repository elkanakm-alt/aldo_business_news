@php use Illuminate\Support\Str; @endphp

<aside class="space-y-10 sticky top-28 h-fit">

    {{-- ================= CATÉGORIES ================= --}}
    @if(isset($categories) && $categories->count())
    <div class="card p-6 border rounded-2xl shadow-sm bg-white dark:bg-slate-900">
        <h3 class="font-bold mb-6 text-lg border-l-4 border-emerald-500 pl-3">Catégories</h3>
        <div class="space-y-1">
            @foreach($categories as $cat)
            <a href="{{ route('category.show', $cat->slug) }}"
               class="flex justify-between items-center py-3 border-b border-slate-100 dark:border-slate-800 last:border-none transition hover:text-emerald-500">
                <span class="font-medium text-sm">{{ $cat->name }}</span>
                <span class="text-xs px-2 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500">
                    {{ $cat->posts_count ?? 0 }}
                </span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ================= ARTICLES POPULAIRES ================= --}}
    @if(isset($popularPosts) && $popularPosts->count())
    <div class="card p-6 border rounded-2xl shadow-sm bg-white dark:bg-slate-900">
        <h3 class="font-bold mb-6 text-lg border-l-4 border-emerald-500 pl-3">Articles populaires</h3>
        <div class="space-y-6">
            @foreach($popularPosts as $pop)
            <a href="{{ route('post.show', $pop->slug) }}" class="flex gap-4 items-center group">
                <div class="w-16 h-16 flex-shrink-0 overflow-hidden rounded-xl bg-slate-200">
                    <img src="{{ $pop->image ? asset('storage/'.$pop->image) : asset('images/default.jpg') }}"
                         class="w-full h-full object-cover transition duration-300 group-hover:scale-110"
                         alt="{{ $pop->title }}">
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold group-hover:text-emerald-500 transition line-clamp-2">
                        {{ $pop->title }}
                    </p>
                    <span class="text-[10px] text-slate-500 flex items-center gap-1 mt-1">
                        👁 {{ $pop->views ?? 0 }} vues
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</aside>
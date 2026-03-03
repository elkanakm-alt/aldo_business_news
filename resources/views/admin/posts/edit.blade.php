@extends('layouts.admin')

@section('title', 'Modifier l\'Article')

@section('content')
<div class="py-6 max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <a href="{{ route('admin.posts.index') }}" class="text-blue-600 font-black text-xs uppercase tracking-widest flex items-center gap-2 mb-2 hover:gap-3 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Annuler et retour
            </a>
            <h1 class="text-4xl font-black text-gray-800 dark:text-white tracking-tighter uppercase">Modifier l'article</h1>
        </div>
    </div>

    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        @method('PUT')

        {{-- Colonne GAUCHE : Éditeur (2/3) --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-900 p-8 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-800">
                
                {{-- Titre --}}
                <div class="mb-8">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Titre de la publication</label>
                    <input type="text" name="title" value="{{ old('title', $post->title) }}" placeholder="Titre..." 
                        class="w-full px-0 py-3 bg-transparent border-0 border-b-2 border-gray-100 dark:border-gray-800 text-3xl font-bold focus:ring-0 focus:border-blue-500 transition-all">
                    @error('title') <p class="text-rose-500 text-[10px] mt-2 font-black uppercase">{{ $message }}</p> @enderror
                </div>

                {{-- CKEditor --}}
                <div class="editor-container">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4">Contenu de l'article</label>
                    <textarea name="content" id="editor">{{ old('content', $post->content) }}</textarea>
                    @error('content') <p class="text-rose-500 text-[10px] mt-2 font-black uppercase">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Colonne DROITE : Paramètres (1/3) --}}
        <div class="space-y-6">
            
            {{-- Section Image --}}
            <div class="bg-white dark:bg-gray-900 p-6 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-800">
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4">Image de couverture</label>
                
                <div class="relative group">
                    <input type="file" name="image" id="imageInput" class="hidden" accept="image/*">
                    <label for="imageInput" class="block w-full aspect-square rounded-3xl border-2 border-dashed border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 hover:bg-blue-50 dark:hover:bg-blue-900/10 hover:border-blue-400 transition-all overflow-hidden relative cursor-pointer">
                        
                        {{-- Affichage de l'image actuelle ou du placeholder --}}
                        <div id="previewContainer" class="absolute inset-0 {{ $post->image ? 'hidden' : 'flex' }} flex-col items-center justify-center text-gray-400">
                            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-[9px] font-black uppercase tracking-widest">Remplacer l'image</span>
                        </div>
                        
                        <img id="imagePreview" 
                             src="{{ $post->image ? asset('storage/' . $post->image) : '' }}" 
                             class="{{ $post->image ? '' : 'hidden' }} w-full h-full object-cover">
                    </label>
                </div>
                <p class="text-[9px] text-gray-400 mt-2 italic text-center">Laissez vide pour conserver l'image actuelle</p>
                @error('image') <p class="text-rose-500 text-[10px] mt-2 font-black uppercase">{{ $message }}</p> @enderror
            </div>

            {{-- Section Catégorie --}}
            <div class="bg-white dark:bg-gray-900 p-6 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-800">
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4">Catégorie</label>
                <select name="category_id" class="w-full p-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-xs font-black uppercase tracking-widest focus:ring-2 focus:ring-blue-500 cursor-pointer">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-rose-500 text-[10px] mt-2 font-black uppercase">{{ $message }}</p> @enderror
            </div>

            {{-- Bouton Update --}}
            <button type="submit" class="w-full py-5 bg-green-600 hover:bg-green-700 text-white font-black uppercase tracking-[0.2em] rounded-3xl shadow-xl shadow-green-500/20 transition-all flex items-center justify-center gap-3 group">
                Enregistrer les modifications
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </button>
        </div>
    </form>
</div>

{{-- SCRIPTS CKEDITOR --}}
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<style>
    .ck-editor__edged { border: none !important; }
    .ck-editor__main > .ck-editor__editable {
        background: #f9fafb !important;
        border: none !important;
        border-radius: 0 0 1.5rem 1.5rem !important;
        min-height: 400px;
        padding: 2rem !important;
    }
    .dark .ck-editor__main > .ck-editor__editable { background: #111827 !important; color: white; }
    .ck-toolbar { background: #f3f4f6 !important; border: none !important; border-radius: 1.5rem 1.5rem 0 0 !important; }
    .dark .ck-toolbar { background: #1f2937 !important; }
</style>

<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
        })
        .catch(error => { console.error(error); });

    document.getElementById('imageInput').onchange = evt => {
        const [file] = document.getElementById('imageInput').files
        if (file) {
            document.getElementById('imagePreview').src = URL.createObjectURL(file)
            document.getElementById('imagePreview').classList.remove('hidden')
            document.getElementById('previewContainer').classList.add('hidden')
        }
    }
</script>
@endsection
@extends('layouts.admin') {{-- Ou ton layout habituel --}}

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-black text-slate-800 mb-8 tracking-tight">Mon Profil Admin</h1>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Section Photo --}}
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-sm border border-slate-100 text-center">
                <div class="relative inline-block">
                    <img id="avatar-preview" 
                         {{-- On utilise l'accessor défini dans le modèle User --}}
                         src="{{ auth()->user()->profile_photo }}" 
                         class="w-32 h-32 rounded-[2rem] object-cover border-4 border-white shadow-lg" alt="Avatar">
                    
                    <label for="photo-input" class="absolute -bottom-2 -right-2 bg-blue-600 p-2 rounded-xl text-white cursor-pointer hover:scale-110 transition-all shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </label>
                    {{-- Très important : name="photo" pour correspondre au contrôleur --}}
                    <input type="file" name="photo" id="photo-input" class="hidden" accept="image/*" onchange="previewImage(event)">
                </div>
                <p class="mt-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Photo de profil</p>
            </div>

            {{-- Section Formulaire --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Nom complet</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Email</label>
                            <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-5 bg-blue-600 text-white rounded-[1.5rem] font-black uppercase tracking-widest shadow-xl shadow-blue-500/30 hover:bg-blue-700 transition-all">
                    Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('avatar-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
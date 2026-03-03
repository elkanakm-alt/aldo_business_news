@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('admin.contacts.index') }}" class="text-xs font-bold text-gray-400 hover:text-blue-600">
        ← Retour
    </a>

    <div class="bg-white dark:bg-gray-900 p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm">
        <h2 class="text-xl font-black mb-4">{{ $contact->subject }}</h2>
        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-xl text-sm mb-6">
            {{ $contact->message }}
        </div>

        <h3 class="text-emerald-500 font-bold text-sm uppercase mb-4">Votre réponse</h3>
        
        <form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST" class="space-y-4">
            @csrf
            <textarea 
                name="reply_message" 
                rows="6" 
                class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500"
                placeholder="Écrivez ici..."
                required
            ></textarea>

            <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-blue-700 transition-all">
                Envoyer la réponse
            </button>
        </form>
    </div>
</div>
@endsection
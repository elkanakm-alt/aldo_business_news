@extends('layouts.admin')

@section('title', 'Messages de Contact')

@section('content')
<div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden">
    {{-- Header --}}
    <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-gray-800 dark:text-white uppercase tracking-tight">Messages Reçus</h1>
            <p class="text-sm text-gray-500 mt-1 italic">Gestion des interactions utilisateurs</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest">
                {{ $contacts->total() }} Total
            </span>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-gray-800/50 text-[10px] uppercase tracking-widest text-gray-400 font-black">
                    <th class="px-8 py-4">Expéditeur</th>
                    <th class="px-8 py-4">Sujet</th>
                    <th class="px-8 py-4">Statut</th>
                    <th class="px-8 py-4">Date</th>
                    <th class="px-8 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($contacts as $contact)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-all duration-200">
                    <td class="px-8 py-5">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-800 dark:text-gray-200">{{ $contact->name }}</span>
                            <span class="text-xs text-gray-400 lowercase">{{ $contact->email }}</span>
                        </div>
                    </td>

                    <td class="px-8 py-5">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                            {{ Str::limit($contact->subject, 40) }}
                        </span>
                    </td>

                    <td class="px-8 py-5">
                        @if($contact->is_read)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400 text-[10px] font-black uppercase">
                                <i class="fa-solid fa-check-double text-[8px]"></i> Lu
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-900/20 dark:text-rose-400 text-[10px] font-black uppercase tracking-tighter animate-pulse">
                                <i class="fa-solid fa-circle text-[6px]"></i> Nouveau
                            </span>
                        @endif
                    </td>

                    <td class="px-8 py-5 text-xs text-gray-400 font-medium italic">
                        {{ $contact->created_at->diffForHumans() }}
                    </td>

                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end items-center gap-2">
                            <a href="{{ route('admin.contacts.show', $contact->id) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-blue-600 hover:text-white transition-all font-bold text-xs shadow-sm">
                                <i class="fa-solid fa-eye text-[10px]"></i> Voir
                            </a>

                            <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <span class="text-4xl mb-4">☕</span>
                            <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">Inbox Zero !</h3>
                            <p class="text-gray-400 italic text-sm">Aucun message trouvé dans la base de données.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($contacts->hasPages())
    <div class="p-6 border-t border-gray-100 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-800/30">
        {{ $contacts->links() }}
    </div>
    @endif
</div>
@endsection
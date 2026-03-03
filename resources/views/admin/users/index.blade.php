@extends('layouts.admin')

@section('title', 'Liste des Utilisateurs')

@section('content')
<div class="space-y-6">
    {{-- EN-TÊTE --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 dark:text-white tracking-tight">Utilisateurs</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                @if(request('search'))
                    Résultats pour <span class="text-blue-600 font-bold">"{{ request('search') }}"</span> ({{ $users->total() }})
                @else
                    <span class="text-blue-600 dark:text-blue-400">{{ $users->total() }}</span> membres enregistrés au total
                @endif
            </p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-blue-500/25 active:scale-95">
            <i class="fa-solid fa-plus text-sm"></i>
            <span>Nouvel Utilisateur</span>
        </a>
    </div>

    {{-- BARRE DE RECHERCHE ET FILTRES --}}
    <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Rechercher un nom ou un email..." 
                    class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 dark:bg-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all dark:text-white">
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="flex-1 md:flex-none bg-gray-800 dark:bg-blue-600 hover:bg-gray-900 dark:hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter text-xs"></i>
                    Filtrer
                </button>

                {{-- BOUTON VOIR TOUT --}}
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="flex-1 md:flex-none bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-6 py-3 rounded-xl font-bold hover:bg-gray-200 dark:hover:bg-gray-600 transition-all flex items-center justify-center gap-2 border border-gray-200 dark:border-gray-600">
                        <i class="fa-solid fa-eye text-xs"></i>
                        Voir tout
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABLEAU --}}
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-700/50">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center w-20">ID</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Membre</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest hidden md:table-cell">Contact</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Rôle</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($users as $user)
                    <tr class="group hover:bg-blue-50/30 dark:hover:bg-blue-900/5 transition-colors">
                        <td class="px-6 py-5 text-center">
                            <span class="text-sm font-black text-gray-400">#{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-black text-lg shadow-lg shadow-blue-200 dark:shadow-none transition-transform group-hover:scale-110">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    @if($user->is_admin)
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 dark:text-gray-100 leading-none">{{ $user->name }}</p>
                                    <p class="text-[11px] text-gray-400 mt-1 font-medium italic">
                                        {{ $user->posts_count ?? 0 }} {{ Str::plural('article', $user->posts_count) }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 hidden md:table-cell">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $user->email }}</span>
                                <span class="text-[10px] text-blue-500 font-bold uppercase tracking-tighter">Inscrit {{ $user->created_at->translatedFormat('d M Y') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($user->is_admin)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-black bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400 uppercase tracking-wider border border-indigo-100 dark:border-indigo-800">
                                    <i class="fa-solid fa-shield-crown"></i> Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-black bg-gray-50 text-gray-500 dark:bg-gray-700/50 dark:text-gray-400 uppercase tracking-wider border border-gray-100 dark:border-gray-600">
                                    <i class="fa-solid fa-user"></i> User
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">
                                    @csrf @method('DELETE')
                                    <button class="w-9 h-9 flex items-center justify-center bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-user-slash text-3xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 font-bold text-lg">Aucun utilisateur trouvé</p>
                                <p class="text-gray-400 text-sm">Essayez de cliquer sur "Voir tout" pour réinitialiser.</p>
                                <a href="{{ route('admin.users.index') }}" class="mt-4 text-blue-600 font-bold hover:underline">Réinitialiser la recherche</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="px-6 py-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Tableau de Bord')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- 1. CARTES KPI (Statistiques) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="h-12 w-12 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-2xl flex items-center justify-center">
                <i class="fa-solid fa-newspaper text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Articles</p>
                <h3 class="text-2xl font-black dark:text-white">{{ $totalPosts ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="h-12 w-12 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 rounded-2xl flex items-center justify-center">
                <i class="fa-solid fa-users text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Lecteurs</p>
                <h3 class="text-2xl font-black dark:text-white">{{ $totalUsers ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="h-12 w-12 bg-amber-50 dark:bg-amber-900/20 text-amber-500 rounded-2xl flex items-center justify-center">
                <i class="fa-solid fa-eye text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Vues Totales</p>
                <h3 class="text-2xl font-black dark:text-white">{{ number_format($totalViews ?? 0) }}</h3>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="h-12 w-12 bg-rose-50 dark:bg-rose-900/20 text-rose-500 rounded-2xl flex items-center justify-center">
                <i class="fa-solid fa-heart text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Likes</p>
                <h3 class="text-2xl font-black dark:text-white">{{ number_format($totalLikes ?? 0) }}</h3>
            </div>
        </div>
    </div>

    {{-- 2. ZONE DES GRAPHIQUES --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-black dark:text-white flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-500 rounded-full"></span> Analyses de croissance
                </h2>
                <div class="flex gap-4 text-[10px] font-bold uppercase tracking-tighter">
                    <span class="flex items-center gap-1 text-blue-500"><i class="fa-solid fa-circle text-[6px]"></i> Vues</span>
                    <span class="flex items-center gap-1 text-emerald-500"><i class="fa-solid fa-circle text-[6px]"></i> Likes</span>
                </div>
            </div>
            <div class="h-[300px] w-full">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col items-center">
            <h2 class="text-xl font-black mb-6 dark:text-white w-full text-left">Catégories</h2>
            <div class="h-[250px] w-full flex justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    {{-- 3. ARTICLES RÉCENTS & MODÉRATION --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-black dark:text-white">Derniers articles</h2>
                <a href="{{ route('admin.posts.index') }}" class="text-blue-500 text-[10px] font-black uppercase tracking-widest hover:underline italic">Voir tout l'historique</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 dark:border-slate-800">
                            <th class="pb-4">Titre</th>
                            <th class="pb-4">Vues</th>
                            <th class="pb-4 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        @forelse($latestPosts as $post)
                        <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-4">
                                <span class="text-sm font-bold dark:text-slate-200">{{ Str::limit($post->title, 40) }}</span>
                            </td>
                            <td class="py-4">
                                <span class="text-[10px] font-black px-2 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-lg">{{ $post->views }}</span>
                            </td>
                            <td class="py-4 text-right text-xs text-slate-400 font-medium">
                                {{ $post->created_at->format('d M, Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="py-8 text-center text-slate-400 italic">Aucun article publié.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-black dark:text-white">Commentaires</h2>
                <span class="bg-amber-500 text-white text-[9px] px-2 py-0.5 rounded-full font-black animate-pulse uppercase">Modération</span>
            </div>

            <ul class="space-y-4">
                @forelse($recentComments as $comment)
                <li class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border-l-4 border-amber-500">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-[10px] font-black uppercase text-blue-500 italic">{{ $comment->name ?? 'Anonyme' }}</span>
                        <span class="text-[9px] text-slate-400 font-bold">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300 line-clamp-2 italic">
                        "{{ $comment->content }}"
                    </p>
                </li>
                @empty
                <li class="text-center py-8 text-slate-400 italic text-xs">Aucun nouveau commentaire.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. CONFIGURATION CHART CROISSANCE ---
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels ?? []) !!},
                datasets: [{
                    label: 'Vues',
                    data: {!! json_encode($dataViews ?? []) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 4,
                    pointRadius: 0,
                    pointHoverRadius: 6
                }, {
                    label: 'Likes',
                    data: {!! json_encode($dataLikes ?? []) !!},
                    borderColor: '#10b981',
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { weight: 'bold', size: 10 } } },
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.03)' } }
                }
            }
        });

        // --- 2. CONFIGURATION CHART CATÉGORIES ---
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($catLabels ?? []) !!},
                datasets: [{
                    data: {!! json_encode($catCounts ?? []) !!},
                    backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true, font: { weight: 'bold', size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endpush
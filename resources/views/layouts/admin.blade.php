<!DOCTYPE html>
<html lang="fr" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - AdminHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
    
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="flex min-h-screen bg-gray-50 dark:bg-gray-950 font-sans text-gray-800 dark:text-gray-200 overflow-x-hidden">

    {{-- OVERLAY MOBILE : Pour fermer la sidebar en cliquant à côté --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900/50 z-20 hidden md:hidden backdrop-blur-sm"></div>

    {{-- SIDEBAR : Ajout de -translate-x-full par défaut sur mobile --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 transition-all duration-300 transform -translate-x-full md:relative md:translate-x-0 flex flex-col shrink-0">
        <div class="h-full flex flex-col">
            {{-- LOGO --}}
            <div class="h-16 flex items-center px-6 font-bold text-2xl text-blue-600 dark:text-blue-400 gap-2 shrink-0">
                <span class="text-3xl">🚀</span><span id="logo-text" class="flex">
                    <span class="text-blue-600">AL</span><span class="text-red-600">DO</span><span class="bg-gradient-to-r from-cyan-500 to-blue-600 bg-clip-text text-transparent">ADMIN</span>
                </span>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                <p class="sidebar-text px-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 mt-4">Menu Principal</p>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }} transition-all">
                    <i class='bx bxs-dashboard text-xl'></i>
                    <span class="font-bold sidebar-text text-sm">Dashboard</span>
                </a>

                <a href="{{ route('admin.posts.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.posts.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }} transition-all">
                    <i class='bx bxs-news text-xl'></i>
                    <span class="font-bold sidebar-text text-sm">Articles</span>
                </a>

                <a href="{{ route('admin.comments.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.comments.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }} transition-all group">
                    <div class="flex items-center gap-3">
                        <i class='bx bxs-message-dots text-xl'></i>
                        <span class="font-bold sidebar-text text-sm">Commentaires</span>
                    </div>
                    @if(isset($unreadCommentsCount) && $unreadCommentsCount > 0)
                        <span class="sidebar-text inline-flex items-center justify-center px-2 py-1 text-[10px] font-black text-white bg-amber-500 rounded-lg">
                            {{ $unreadCommentsCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.contacts.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.contacts.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }} transition-all group">
                    <div class="flex items-center gap-3">
                        <i class='bx bxs-envelope text-xl'></i>
                        <span class="font-bold sidebar-text text-sm">Messages</span>
                    </div>
                    @if(isset($unreadContactsCount) && $unreadContactsCount > 0)
                        <span class="sidebar-text inline-flex items-center justify-center px-2 py-1 text-[10px] font-black text-white bg-rose-500 rounded-lg">
                            {{ $unreadContactsCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }} transition-all">
                    <i class='bx bxs-category text-xl'></i>
                    <span class="font-bold sidebar-text text-sm">Catégories</span>
                </a>

                <p class="sidebar-text px-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 mt-8">Configuration</p>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }} transition-all">
                    <i class='bx bxs-group text-xl'></i>
                    <span class="font-bold sidebar-text text-sm">Utilisateurs</span>
                </a>

                <button id="theme-toggle" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                    <i id="theme-toggle-dark-icon" class='bx bxs-moon text-xl hidden'></i>
                    <i id="theme-toggle-light-icon" class='bx bxs-sun text-xl hidden'></i>
                    <span class="font-bold sidebar-text text-sm">Thème</span>
                </button>
                
                <form method="POST" action="{{ route('logout') }}" class="pt-4 border-t border-gray-100 dark:border-gray-800 mt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all">
                        <i class='bx bx-log-out-circle text-xl'></i>
                        <span class="font-black sidebar-text text-sm">Sortie</span>
                    </button>
                </form>
            </nav>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto">
        
        {{-- HEADER --}}
        <header class="sticky top-0 z-20 h-16 flex items-center justify-between px-6 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 shrink-0">
            <div class="flex items-center gap-4">
                <button id="toggleSidebar" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i class='bx bx-menu text-2xl'></i>
                </button>
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest hidden lg:block ">Espace Management</h2>
            </div>

            <div class="flex items-center gap-5">
                {{-- CLOCHE DYNAMIQUE --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-blue-500 transition-colors focus:outline-none">
                        <i class='bx bxs-bell text-2xl'></i>
                        @if(isset($totalNotifications) && $totalNotifications > 0)
                            <span class="absolute top-2 right-2 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500 text-[9px] text-white font-bold items-center justify-center">
                                    {{ $totalNotifications }}
                                </span>
                            </span>
                        @endif
                    </button>

                    <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 mt-3 w-80 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center border-b border-gray-100 dark:border-gray-700">
                            <span class="font-black text-xs uppercase tracking-tighter">Alertes & Activités</span>
                        </div>
                        <div class="max-h-72 overflow-y-auto">
                            @if(isset($unreadContactsCount) && $unreadContactsCount > 0)
                                <a href="{{ route('admin.contacts.index') }}" class="block p-4 border-b border-gray-50 dark:border-gray-700/50 hover:bg-blue-50 dark:hover:bg-blue-900/10">
                                    <p class="text-xs font-bold text-blue-600 uppercase">📧 {{ $unreadContactsCount }} Nouveau(x) Message(s)</p>
                                </a>
                            @endif
                            @forelse($latestNotifications ?? [] as $notification)
                                <a href="#" class="block p-4 border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 transition-colors">
                                    <p class="text-xs font-bold uppercase">{{ $notification->data['user_name'] ?? 'Système' }}</p>
                                </a>
                            @empty
                                <div class="p-8 text-center text-gray-400 italic text-xs">Aucune alerte ✨</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- USER PROFILE --}}
                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 pl-4 border-l border-gray-100 dark:border-gray-800 group transition-all">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-black text-gray-800 dark:text-white leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-blue-500 font-bold mt-1 uppercase tracking-tighter">Admin</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black shadow-lg overflow-hidden border-2 border-transparent group-hover:border-blue-500/20">
                        @if(auth()->user()->photo)
                            <img src="{{ asset('storage/' . auth()->user()->photo) }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                </a>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 p-4 lg:p-10">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-8 p-4 bg-emerald-500/10 border-l-4 border-emerald-500 text-emerald-600 text-sm font-bold rounded-r-xl shadow-sm flex justify-between items-center">
                        <span>✨ {{ session('success') }}</span>
                        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">×</button>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        const logoText = document.getElementById('logo-text');
        
        // Fonction pour basculer la sidebar
        function toggleSidebar() {
            if (window.innerWidth >= 768) {
                // Version Desktop : Réduction / Agrandissement
                sidebar.classList.toggle('w-64');
                sidebar.classList.toggle('w-20');
                sidebarTexts.forEach(text => text.classList.toggle('hidden'));
                logoText.classList.toggle('hidden');
            } else {
                // Version Mobile : Entrée / Sortie (Slide)
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            }
        }

        toggleBtn.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Theme Toggle
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        function updateIcons() {
            if (document.documentElement.classList.contains('dark')) {
                lightIcon.classList.remove('hidden');
                darkIcon.classList.add('hidden');
            } else {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }
        }
        updateIcons();

        themeToggleBtn.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('color-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            updateIcons();
        });
    </script>
</body>
</html>
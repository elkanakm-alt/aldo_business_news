<!DOCTYPE html>
<html lang="fr" 
      x-data="{ dark: localStorage.getItem('theme') === 'dark', open: false }" 
      x-init="$watch('dark', val => localStorage.setItem('theme', val ? 'dark' : 'light'))"
      :class="{ 'dark': dark }" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Modification du Title --}}
    <title>@yield('title', 'ALDO NEWS')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        .nav-link { @apply relative text-sm font-medium transition-colors duration-300 hover:text-cyan-500; }
        .nav-link::after { @apply content-[''] absolute left-0 -bottom-1 w-0 h-0.5 bg-cyan-500 transition-all duration-300; }
        .nav-link:hover::after { @apply w-full; }
        .footer-link { @apply text-slate-500 dark:text-slate-400 hover:text-cyan-500 transition-colors duration-200 text-sm; }
        
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 10s linear infinite;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 transition-colors duration-300 font-sans">

    <header class="sticky top-0 z-50 backdrop-blur-xl bg-white/80 dark:bg-slate-900/90 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                {{-- LOGO MODIFIÉ : AL (Bleu) DO (Rouge) --}}
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-2xl font-black tracking-tight">
                        <span class="text-blue-600">AL</span><span class="text-red-600">DO</span><span class="bg-gradient-to-r from-cyan-500 to-blue-600 bg-clip-text text-transparent">_NEWS</span>
                    </a>
                </div>

                {{-- NAVIGATION DESKTOP --}}
                <nav class="hidden lg:flex items-center gap-10">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'text-cyan-500' : '' }}">Accueil</a>
                    <a href="{{ route('bible') }}" class="nav-link {{ request()->routeIs('bible') ? 'text-cyan-500' : '' }}">Bible</a>
                    <a href="{{ route('contact.show') }}" class="nav-link {{ request()->routeIs('contact.show') ? 'text-cyan-500' : '' }}">Contact</a>
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'text-cyan-500' : '' }}">À propos</a>
                </nav>

                {{-- ACTIONS / PROFIL DESKTOP --}}
                <div class="hidden md:flex items-center gap-6">
                    <button @click="dark = !dark" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:scale-110 transition-transform shadow-sm">
                        <span x-show="!dark">☀️</span><span x-show="dark" x-cloak>🌙</span>
                    </button>

                    <div class="h-6 w-px bg-slate-200 dark:bg-slate-700"></div>

                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-semibold hover:text-cyan-500 transition">Connexion</a>
                        <a href="{{ route('register') }}" class="px-6 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-bold rounded-full transition-all shadow-lg shadow-cyan-500/25 active:scale-95">
                            S'INSCRIRE
                        </a>
                    @else
                        <div class="flex items-center gap-5">
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="text-[10px] font-black uppercase tracking-widest text-cyan-500 hover:text-cyan-600 transition-colors">
                                    Admin
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                                @csrf
                                <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-rose-500 hover:text-rose-600 transition-colors">
                                    Déconnexion
                                </button>
                            </form>

                            <a href="{{ route('dashboard') }}" class="relative group flex items-center gap-3 pl-4 border-l border-slate-200 dark:border-slate-800">
                                <div class="absolute -inset-1 bg-gradient-to-r from-amber-400 to-cyan-500 rounded-2xl opacity-0 group-hover:opacity-10 blur transition duration-500"></div>
                                
                                <div class="text-right relative">
                                    <div class="flex items-center justify-end gap-1">
                                        <p class="text-xs font-black text-slate-900 dark:text-white leading-none group-hover:text-cyan-500 transition-colors">
                                            {{ Auth::user()->name }}
                                        </p>
                                        <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                                    </div>
                                    <p class="text-[9px] text-amber-500 font-black uppercase mt-1 tracking-tighter">Premium</p>
                                </div>

                                <div class="relative h-11 w-11 flex-shrink-0 group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute -inset-0.5 bg-gradient-to-tr from-amber-400 via-yellow-200 to-amber-600 rounded-xl animate-spin-slow opacity-70 group-hover:opacity-100"></div>
                                    <div class="relative h-full w-full rounded-xl bg-slate-900 overflow-hidden border-2 border-white dark:border-slate-900 flex items-center justify-center shadow-lg">
                                        @if(Auth::user()->photo)
                                            <img src="{{ asset('storage/' . Auth::user()->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-white font-black text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endguest
                </div>

                {{-- MENU MOBILE BUTTON --}}
                <div class="md:hidden flex items-center">
                    <button @click="open = !open" class="text-slate-600 dark:text-slate-300 p-2">
                        <svg x-show="!open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        <svg x-show="open" x-cloak class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- MOBILE NAVIGATION --}}
        <div x-show="open" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             @click.away="open = false" 
             class="md:hidden bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-6 py-8 shadow-2xl">
            
            <nav class="flex flex-col gap-6 mb-8">
                <a href="{{ route('home') }}" class="text-xl font-bold hover:text-cyan-500 transition">Accueil</a>
                <a href="{{ route('bible') }}" class="text-xl font-bold hover:text-cyan-500 transition">Bible</a>
                <a href="{{ route('contact.show') }}" class="text-xl font-bold hover:text-cyan-500 transition">Contact</a>
                <a href="{{ route('about') }}" class="text-xl font-bold hover:text-cyan-500 transition">À propos</a>
            </nav>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 space-y-6">
                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl">
                    <span class="font-bold text-sm uppercase tracking-wider">Apparence</span>
                    <button @click="dark = !dark" class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 rounded-xl shadow-sm border border-slate-200 dark:border-slate-600 transition">
                        <span x-show="!dark">☀️ Mode Clair</span>
                        <span x-show="dark" x-cloak>🌙 Mode Sombre</span>
                    </button>
                </div>

                @guest
                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <a href="{{ route('login') }}" class="flex items-center justify-center py-4 border border-slate-200 dark:border-slate-700 rounded-2xl font-bold text-sm">Connexion</a>
                        <a href="{{ route('register') }}" class="flex items-center justify-center py-4 bg-cyan-500 text-white rounded-2xl font-bold text-sm shadow-lg shadow-cyan-500/20">S'inscrire</a>
                    </div>
                @else
                    <div class="flex flex-col gap-4">
                        <div class="flex justify-between items-center p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border-2 border-amber-500/20">
                            <a href="{{ route('dashboard') }}" class="font-black text-amber-500 uppercase text-xs tracking-widest flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-gradient-to-tr from-amber-400 to-amber-600 text-white flex items-center justify-center text-xs shadow-md">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span>Mon Profil Premium</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-rose-500 font-bold text-xs uppercase tracking-widest">Déconnexion</button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER MODIFIÉ --}}
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                
                <div class="col-span-1 md:col-span-1 space-y-4">
                    <a href="{{ route('home') }}" class="text-xl font-black">
                        <span class="text-blue-600">AL</span><span class="text-red-600">DO</span><span class="bg-gradient-to-r from-cyan-500 to-blue-600 bg-clip-text text-transparent">_NEWS</span>
                    </a>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                        Source d'information indépendante. Business, technologie et actualités internationales.
                    </p>
                </div>

                <div class="space-y-4">
                    <h4 class="text-sm font-bold uppercase tracking-widest text-slate-900 dark:text-white">Navigation</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="footer-link">Accueil</a></li>
                        <li><a href="{{ route('bible') }}" class="footer-link">La Bible</a></li>
                        <li><a href="{{ route('contact.show') }}" class="footer-link">Contact</a></li>
                        <li><a href="{{ route('about') }}" class="footer-link">À propos</a></li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <h4 class="text-sm font-bold uppercase tracking-widest text-slate-900 dark:text-white">Légal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="footer-link">Confidentialité</a></li>
                        <li><a href="#" class="footer-link">Mentions Légales</a></li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <h4 class="text-sm font-bold uppercase tracking-widest text-slate-900 dark:text-white">Newsletter</h4>
                    <form class="flex flex-col gap-2">
                        <input type="email" placeholder="Votre Email" class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 border-none text-sm focus:ring-2 focus:ring-cyan-500 outline-none transition-all">
                        <button type="submit" class="bg-cyan-500 text-white py-2.5 rounded-xl text-sm font-bold hover:bg-cyan-600 transition shadow-lg shadow-cyan-500/20">
                            S'abonner
                        </button>
                    </form>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-[10px] md:text-xs text-slate-400 uppercase tracking-widest">
                    &copy; 2026 <span class="text-blue-600">AL</span><span class="text-red-600 font-bold">DO</span>_NEWS. Tous droits réservés.
                </p>
                <div class="flex gap-6 grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition duration-500">
                    <span class="cursor-pointer">𝕏</span>
                    <span class="cursor-pointer">🅵</span>
                    <span class="cursor-pointer">🅸</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
    function likePost(postId) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/post/${postId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.querySelectorAll('[id^="like-count-' + postId + '"]').forEach(el => {
                    el.innerText = data.likes;
                });
            }
        })
        .catch(err => console.error('Erreur:', err));
    }
    </script>
</body>
</html>
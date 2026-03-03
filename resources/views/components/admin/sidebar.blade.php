<aside class="w-64 h-screen flex flex-col bg-gradient-to-b from-indigo-500 via-purple-500 to-pink-500 text-white shadow-lg">
    {{-- HEADER --}}
    <div class="p-6 text-center font-bold text-xl border-b border-white/20">
        Admin Panel
    </div>

    {{-- NAVIGATION --}}
    <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 p-3 rounded hover:bg-white/20 transition">
            <span class="text-2xl">🏠</span>
            <span class="font-medium">Dashboard</span>
        </a>
        <a href="{{ route('admin.posts.index') }}"
           class="flex items-center gap-3 p-3 rounded hover:bg-white/20 transition">
            <span class="text-2xl">📝</span>
            <span class="font-medium">Articles</span>
        </a>
        <a href="{{ route('admin.categories.index') }}"
           class="flex items-center gap-3 p-3 rounded hover:bg-white/20 transition">
            <span class="text-2xl">📂</span>
            <span class="font-medium">Catégories</span>
        </a>
        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 p-3 rounded hover:bg-white/20 transition">
            <span class="text-2xl">👤</span>
            <span class="font-medium">Utilisateurs</span>
        </a>
    </nav>

    {{-- DARK / LIGHT TOGGLE --}}
    <div class="p-4 border-t border-white/20">
        <button id="darkModeToggle" 
                class="w-full py-2 rounded-lg font-semibold text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
            🌙 Dark / Light
        </button>
    </div>

    {{-- SCRIPT DARK MODE --}}
    <script>
        const btn = document.getElementById('darkModeToggle');
        btn.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            // Sauvegarde le choix dans le localStorage
            if(document.documentElement.classList.contains('dark')){
                localStorage.setItem('theme','dark');
            } else {
                localStorage.setItem('theme','light');
            }
        });

        // Appliquer le thème sauvegardé
        if(localStorage.getItem('theme') === 'dark'){
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</aside>
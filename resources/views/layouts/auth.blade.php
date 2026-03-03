<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    @vite('resources/css/app.css')

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>

<body class="min-h-screen flex items-center justify-center
             bg-gradient-to-br from-pink-100 via-white to-orange-100
             dark:from-gray-900 dark:via-gray-950 dark:to-black
             transition duration-300">

<div class="w-full max-w-md">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <h1 class="text-4xl font-extrabold bg-gradient-to-r 
                   from-pink-500 to-orange-500 
                   bg-clip-text text-transparent">
            Admin Panel
        </h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2">
            @yield('subtitle')
        </p>
    </div>

    {{-- Card --}}
    <div class="bg-white/80 dark:bg-gray-900/80 
                backdrop-blur-xl 
                rounded-3xl shadow-2xl p-8 border 
                border-white/30 dark:border-gray-700">

        @yield('content')

    </div>

    <div class="text-center mt-6 text-xs text-gray-500 dark:text-gray-400">
        © 2026 ALDO NEWS
    </div>

</div>

</body>
</html>
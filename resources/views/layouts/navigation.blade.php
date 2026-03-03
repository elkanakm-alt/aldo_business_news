<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Logo -->
            <div class="flex items-center space-x-8">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <x-application-logo class="block h-9 w-auto fill-current text-indigo-600" />
                </a>

                <!-- Desktop Links -->
                <div class="hidden sm:flex space-x-6">
                    <a href="{{ route('dashboard') }}"
                       class="text-gray-700 hover:text-indigo-600 font-medium transition">
                        Dashboard
                    </a>
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">

                @guest
                    <a href="{{ route('login') }}"
                       class="text-gray-600 hover:text-indigo-600 font-medium transition">
                        Connexion
                    </a>

                    <a href="{{ route('register') }}"
                       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow">
                        Inscription
                    </a>
                @endguest


                @auth
                <div class="relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center space-x-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                <span class="font-medium text-gray-700">
                                    {{ Auth::user()->name }}
                                </span>
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 text-sm text-gray-500">
                                {{ Auth::user()->email }}
                            </div>

                            <x-dropdown-link :href="route('profile.edit')">
                                Profil
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Déconnexion
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
                @endauth

            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="p-2 rounded-md text-gray-500 hover:text-indigo-600 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                              class="inline-flex"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}"
               class="block text-gray-700 hover:text-indigo-600 py-2">
                Dashboard
            </a>
        </div>

        @guest
        <div class="border-t border-gray-200 px-4 py-3 space-y-2">
            <a href="{{ route('login') }}"
               class="block text-gray-700 hover:text-indigo-600">
                Connexion
            </a>

            <a href="{{ route('register') }}"
               class="block bg-indigo-600 text-white px-4 py-2 rounded-lg text-center">
                Inscription
            </a>
        </div>
        @endguest

        @auth
        <div class="border-t border-gray-200 px-4 py-4">
            <div class="mb-3">
                <div class="font-medium text-gray-800">
                    {{ Auth::user()->name }}
                </div>
                <div class="text-sm text-gray-500">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <a href="{{ route('profile.edit') }}"
               class="block py-2 text-gray-700 hover:text-indigo-600">
                Profil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); this.closest('form').submit();"
                   class="block py-2 text-red-600 hover:text-red-800">
                    Déconnexion
                </a>
            </form>
        </div>
        @endauth
    </div>
</nav>

<!-- Spacer because navbar is fixed -->
<div class="h-16"></div>

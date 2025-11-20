@props(['current' => ''])

<div
    class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200/20 dark:border-gray-700/30 transition-colors duration-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo & Title -->
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        @if($current === 'dashboard')
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        @elseif($current === 'user-analytics')
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        @elseif($current === 'transactions')
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @else
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        @endif
                    </div>
                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                </div>
                <div>
                    <h1
                        class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                        TradingPro Analytics
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                        @if($current === 'dashboard')
                            Dashboard
                        @elseif($current === 'user-analytics')
                            User Analytics
                        @elseif($current === 'transactions')
                            Transaction Analytics
                        @else
                            Analytics
                        @endif
                    </p>
                </div>
            </div>

            <!-- Enhanced Navigation -->
            <div class="flex items-center space-x-6">
                <nav
                    class="hidden md:flex items-center space-x-2 bg-gray-50/50 dark:bg-gray-800/50 rounded-full p-1 border border-gray-200/50 dark:border-gray-700/50">
                    <a href="{{ route('dashboard') }}" @class([
                        'px-6 py-2.5 text-sm font-medium rounded-full transition-all duration-300 hover:shadow-md backdrop-blur-sm',
                        'relative font-semibold bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 hover:scale-105' => $current === 'dashboard',
                        'text-gray-700 hover:text-gray-900 hover:bg-white/60 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700/60' => $current !== 'dashboard'
                    ])>
                        @if($current === 'dashboard')
                            <span class="relative z-10">Dashboard</span>
                        @else
                            Dashboard
                        @endif
                    </a>
                    <a href="{{ route('user-analytics') }}" @class([
                        'px-6 py-2.5 text-sm font-medium rounded-full transition-all duration-300 hover:shadow-md backdrop-blur-sm',
                        'relative font-semibold bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 hover:scale-105' => $current === 'user-analytics',
                        'text-gray-700 hover:text-gray-900 hover:bg-white/60 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700/60' => $current !== 'user-analytics'
                    ])>
                        @if($current === 'user-analytics')
                            <span class="relative z-10">Users</span>
                        @else
                            Users
                        @endif
                    </a>
                    <a href="{{ route('transactions') }}" @class([
                        'px-6 py-2.5 text-sm font-medium rounded-full transition-all duration-300 hover:shadow-md backdrop-blur-sm',
                        'relative font-semibold bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 hover:scale-105' => $current === 'transactions',
                        'text-gray-700 hover:text-gray-900 hover:bg-white/60 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700/60' => $current !== 'transactions'
                    ])>
                        @if($current === 'transactions')
                            <span class="relative z-10">Transactions</span>
                        @else
                            Transactions
                        @endif
                    </a>
                </nav>

                <!-- Theme Toggle with enhanced design -->
                <div class="flex items-center space-x-3">
                    <button id="themeToggle"
                        class="relative p-3 rounded-full bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 hover:shadow-lg transition-all duration-300 hover:scale-110 group">
                        <svg id="moonIcon"
                            class="w-5 h-5 text-gray-600 dark:text-gray-300 transition-transform duration-300 group-hover:rotate-12"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.017 8.017 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="sunIcon"
                            class="hidden w-5 h-5 text-gray-600 dark:text-gray-300 transition-transform duration-300 group-hover:rotate-180"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <!-- Profile Dropdown -->
                    @auth
                        <div class="relative hidden md:block">
                            <button id="profileDropdownToggle"
                                class="flex items-center space-x-2 p-2 rounded-full bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 hover:shadow-lg transition-all duration-300 hover:scale-105 group">
                                <div
                                    class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-300 transition-transform duration-200 group-hover:rotate-180"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="profileDropdownMenu"
                                class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible transform scale-95 transition-all duration-200 z-50">
                                <div class="p-2">
                                    <div class="px-3 py-2 border-b border-gray-100 dark:border-gray-700">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ Auth::user()->name ?? 'User' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email ??
                                            'user@example.com' }}</p>
                                    </div>

                                    <a href="{{ route('profile.edit') }}"
                                        class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Profile
                                    </a>

                                    <a href="{{ route('profile.edit') }}"
                                        class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Settings
                                    </a>

                                    <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth

                    <!-- Mobile menu button -->
                    <button id="mobileMenuToggle"
                        class="md:hidden p-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-200">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobileMenu" class="md:hidden hidden border-t border-gray-200/50 dark:border-gray-700/50 pt-4 pb-6">
            <nav class="flex flex-col space-y-2">
                <a href="{{ route('dashboard') }}" @class([
                    'px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                    'font-semibold bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg' => $current === 'dashboard',
                    'text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700' => $current !== 'dashboard'
                ])>
                    Dashboard
                </a>
                <a href="{{ route('user-analytics') }}" @class([
                    'px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                    'font-semibold bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg' => $current === 'user-analytics',
                    'text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700' => $current !== 'user-analytics'
                ])>
                    Users
                </a>
                <a href="{{ route('transactions') }}" @class([
                    'px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                    'font-semibold bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg' => $current === 'transactions',
                    'text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700' => $current !== 'transactions'
                ])>
                    Transactions
                </a>

                <!-- Mobile Profile Section -->
                @auth
                    <div class="border-t border-gray-200 dark:border-gray-700 mt-4 pt-4">
                        <div class="flex items-center px-4 py-2 mb-2">
                            <div
                                class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ Auth::user()->name ?? 'User' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email ??
                                    'user@example.com' }}</p>
                            </div>
                        </div>

                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profile
                        </a>

                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center px-4 py-3 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </nav>
        </div>
    </div>
</div>

<!-- Theme Toggle Script -->
<script>
    function initializeNavbar() {
        const themeToggle = document.getElementById('themeToggle');
        const moonIcon = document.getElementById('moonIcon');
        const sunIcon = document.getElementById('sunIcon');
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const profileDropdownToggle = document.getElementById('profileDropdownToggle');
        const profileDropdownMenu = document.getElementById('profileDropdownMenu');

        // Remove any existing listeners to prevent duplicates
        if (profileDropdownToggle && profileDropdownToggle._clickHandler) {
            profileDropdownToggle.removeEventListener('click', profileDropdownToggle._clickHandler);
        }
        if (document._outsideClickHandler) {
            document.removeEventListener('click', document._outsideClickHandler);
        }
        if (profileDropdownMenu && profileDropdownMenu._clickHandler) {
            profileDropdownMenu.removeEventListener('click', profileDropdownMenu._clickHandler);
        }
        if (mobileMenuToggle && mobileMenuToggle._clickHandler) {
            mobileMenuToggle.removeEventListener('click', mobileMenuToggle._clickHandler);
        }
        if (themeToggle && themeToggle._clickHandler) {
            themeToggle.removeEventListener('click', themeToggle._clickHandler);
        }

        // Theme Toggle
        if (themeToggle) {
            const themeHandler = () => {
                document.documentElement.classList.toggle('dark');
                if (moonIcon && sunIcon) {
                    moonIcon.classList.toggle('hidden');
                    sunIcon.classList.toggle('hidden');
                }

                // Save preference and update global state
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    localStorage.setItem('theme', 'dark');
                } else {
                    localStorage.setItem('theme', 'light');
                }

                // Update global theme state
                if (window.__themeState) {
                    window.__themeState.isDark = isDark;
                }
            };
            themeToggle._clickHandler = themeHandler;
            themeToggle.addEventListener('click', themeHandler);
        }

        // Profile Dropdown Toggle
        if (profileDropdownToggle && profileDropdownMenu) {
            const profileHandler = (e) => {
                e.stopPropagation();
                profileDropdownMenu.classList.toggle('opacity-0');
                profileDropdownMenu.classList.toggle('invisible');
                profileDropdownMenu.classList.toggle('scale-95');
            };
            profileDropdownToggle._clickHandler = profileHandler;
            profileDropdownToggle.addEventListener('click', profileHandler);

            // Close dropdown when clicking outside
            const outsideHandler = () => {
                if (!profileDropdownMenu.classList.contains('opacity-0')) {
                    profileDropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                }
            };
            document._outsideClickHandler = outsideHandler;
            document.addEventListener('click', outsideHandler);

            // Prevent dropdown from closing when clicking inside it
            const insideHandler = (e) => {
                e.stopPropagation();
            };
            profileDropdownMenu._clickHandler = insideHandler;
            profileDropdownMenu.addEventListener('click', insideHandler);
        }

        // Mobile Menu Toggle
        if (mobileMenuToggle && mobileMenu) {
            const mobileHandler = () => {
                mobileMenu.classList.toggle('hidden');
                // Close profile dropdown if open
                if (profileDropdownMenu && !profileDropdownMenu.classList.contains('opacity-0')) {
                    profileDropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                }
            };
            mobileMenuToggle._clickHandler = mobileHandler;
            mobileMenuToggle.addEventListener('click', mobileHandler);

            // Close mobile menu when clicking on a link
            const mobileMenuLinks = mobileMenu.querySelectorAll('a');
            mobileMenuLinks.forEach(link => {
                if (!link._clickHandler) {
                    const linkHandler = () => {
                        mobileMenu.classList.add('hidden');
                    };
                    link._clickHandler = linkHandler;
                    link.addEventListener('click', linkHandler);
                }
            });
        }

        // Load saved theme and update icons
        const isDark = window.__themeState?.isDark ??
            (localStorage.getItem('theme') === 'dark' ||
                (localStorage.getItem('theme') === null && window.matchMedia('(prefers-color-scheme: dark)').matches));

        if (moonIcon && sunIcon) {
            if (isDark) {
                moonIcon.classList.add('hidden');
                sunIcon.classList.remove('hidden');
            } else {
                moonIcon.classList.remove('hidden');
                sunIcon.classList.add('hidden');
            }
        }
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', initializeNavbar);

    // Re-initialize after Livewire navigation
    document.addEventListener('livewire:navigated', initializeNavbar);
</script>
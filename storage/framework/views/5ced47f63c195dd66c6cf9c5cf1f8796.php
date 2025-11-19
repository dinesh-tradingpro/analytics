<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Enhanced Modern Header -->
    <div
        class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200/20 dark:border-gray-700/30 transition-colors duration-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo & Title -->
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                    </div>
                    <div>
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            TradingPro Analytics</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Real-time Analytics Dashboard
                        </p>
                    </div>
                </div>

                <!-- Enhanced Navigation -->
                <div class="flex items-center space-x-6">
                    <nav
                        class="hidden md:flex items-center space-x-2 bg-gray-50/50 dark:bg-gray-800/50 rounded-full p-1 border border-gray-200/50 dark:border-gray-700/50">
                        <a href="<?php echo e(route('dashboard')); ?>"
                            class="relative px-6 py-2.5 text-sm font-semibold rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg shadow-blue-500/25 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/30 hover:scale-105">
                            <span class="relative z-10">Dashboard</span>
                        </a>
                        <a href="<?php echo e(route('user-analytics')); ?>"
                            class="px-6 py-2.5 text-sm font-medium rounded-full text-gray-700 hover:text-gray-900 hover:bg-white/60 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700/60 transition-all duration-300 hover:shadow-md backdrop-blur-sm">
                            User Analytics
                        </a>
                        <a href="<?php echo e(route('transactions')); ?>"
                            class="px-6 py-2.5 text-sm font-medium rounded-full text-gray-700 hover:text-gray-900 hover:bg-white/60 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700/60 transition-all duration-300 hover:shadow-md backdrop-blur-sm">
                            Transactions
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
                    <a href="<?php echo e(route('dashboard')); ?>"
                        class="px-4 py-3 text-sm font-semibold rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg">
                        Dashboard
                    </a>
                    <a href="<?php echo e(route('user-analytics')); ?>"
                        class="px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 transition-colors duration-200">
                        User Analytics
                    </a>
                    <a href="<?php echo e(route('transactions')); ?>"
                        class="px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 transition-colors duration-200">
                        Transactions
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Welcome Section -->
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Welcome to TradingPro Analytics</h2>
            <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Get insights into your platform's performance with comprehensive analytics and reports.
            </p>
        </div>

        <!-- Quick Navigation Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- User Analytics Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white ml-3">User Analytics</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    Track new registrations, active users, and user engagement patterns with detailed time-based
                    analytics.
                </p>
                <a href="<?php echo e(route('user-analytics')); ?>"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                    View Analytics
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Transactions Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center mb-4">
                    <div
                        class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white ml-3">Transaction Analytics</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    Monitor transaction volumes, revenue trends, and financial metrics across different time periods.
                </p>
                <a href="<?php echo e(route('transactions')); ?>"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors duration-200">
                    View Transactions
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Reports Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center mb-4">
                    <div
                        class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white ml-3">Reports</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    Generate comprehensive reports and export data for detailed analysis and business insights.
                </p>
                <button
                    class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg font-medium cursor-not-allowed">
                    Coming Soon
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m0 0v2m0-2h2m-2 0h-2m-3-2l-.707-.707M7.05 7.05L4.222 4.222m15.556 15.556L16.95 16.95M16.95 7.05l2.828-2.828M4.222 19.778l2.828-2.828m0-9.9l2.828-2.828m7.424 7.424l2.828 2.828M7.05 16.95l-2.828 2.828" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="mt-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-8 text-white">
            <div class="text-center">
                <h3 class="text-2xl font-bold mb-2">Platform Overview</h3>
                <p class="text-blue-100 mb-6">Real-time insights into your trading platform's performance</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white/10 rounded-lg p-4">
                        <div class="text-3xl font-bold mb-1">24/7</div>
                        <div class="text-blue-100">Monitoring</div>
                    </div>
                    <div class="bg-white/10 rounded-lg p-4">
                        <div class="text-3xl font-bold mb-1">Real-time</div>
                        <div class="text-blue-100">Updates</div>
                    </div>
                    <div class="bg-white/10 rounded-lg p-4">
                        <div class="text-3xl font-bold mb-1">Secure</div>
                        <div class="text-blue-100">Analytics</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Theme Toggle Script -->
    <script>
        const themeToggle = document.getElementById('themeToggle');
        const moonIcon = document.getElementById('moonIcon');
        const sunIcon = document.getElementById('sunIcon');
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        // Theme Toggle
        themeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            moonIcon.classList.toggle('hidden');
            sunIcon.classList.toggle('hidden');

            // Save preference
            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        });

        // Mobile Menu Toggle
        mobileMenuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking on a link
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });

        // Load saved theme
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            moonIcon.classList.add('hidden');
            sunIcon.classList.remove('hidden');
        }
    </script>
</div><?php /**PATH /Users/dineshkumarvalan/git/work/TPAnalytics/resources/views/livewire/dashboard.blade.php ENDPATH**/ ?>
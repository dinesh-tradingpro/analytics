<div class="min-h-screen bg-gray-50 dark:bg-gray-950 transition-colors duration-200">
    <!-- Modern Header -->
    <div
        class="bg-white dark:bg-gray-900 shadow-sm border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & Title -->
                <div class="flex items-center space-x-4">
                    <div
                        class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">TradingPro Analytics</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Comprehensive Time-based Analysis</p>
                    </div>
                </div>

                <!-- Header Controls -->
                <div class="flex items-center space-x-4">
                    <!-- Dark/Light Mode Toggle -->
                    <button id="themeToggle"
                        class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-200">
                        <svg id="moonIcon" class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.017 8.017 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="sunIcon" class="hidden w-5 h-5 text-gray-600 dark:text-gray-300" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <!-- Refresh Button -->
                    <button wire:click="refreshData" wire:loading.attr="disabled"
                        class="flex items-center space-x-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <div wire:loading
                            class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        <span wire:loading.remove>Refresh</span>
                        <span wire:loading>Syncing...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading and Error States -->
    @if($loading)
        <div class="max-w-7xl mx-auto py-8">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 mx-4 sm:mx-6 lg:mx-8">
                <div class="flex items-center justify-center space-x-3">
                    <div class="w-8 h-8 border-4 border-blue-600/20 border-t-blue-600 rounded-full animate-spin"></div>
                    <span class="text-lg font-medium text-gray-700 dark:text-gray-300">Loading Analytics Data...</span>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach($loadingProgress as $key => $status)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                            <span
                                class="font-medium {{ $status === 'Complete' ? 'text-green-600 dark:text-green-400' : ($status === 'Error' ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400') }}">
                                {{ $status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if($error)
        <div class="max-w-7xl mx-auto py-8">
            <div class="bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800 rounded-lg p-4 mx-4 sm:mx-6 lg:mx-8">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Error Loading Data</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $error }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Dashboard Content -->
    @if(!$loading && !$error)
        <div class="max-w-7xl mx-auto py-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 mx-4 sm:mx-6 lg:mx-8">
                <!-- New Users Total -->
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total New Users</h3>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                {{ number_format($newUsersData['total_count'] ?? 0) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Users Total -->
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total Active Users</h3>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                                {{ number_format($activeUsersData['total_count'] ?? 0) }}</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Inactive Users Total -->
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total Inactive Users</h3>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-400">
                                {{ number_format($inactiveUsersData['total_count'] ?? 0) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Users Charts Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    New Users Analytics
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mx-4 sm:mx-6 lg:mx-8">
                    <!-- Daily -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Daily (Last 7 Days)</h3>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded-full">{{ count($newUsersDaily['labels']) }}
                                days</span>
                        </div>
                        <div class="h-64">
                            <canvas id="newUsersDaily"></canvas>
                        </div>
                    </div>

                    <!-- Weekly -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Weekly (Last 4 Weeks)</h3>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded-full">{{ count($newUsersWeekly['labels']) }}
                                weeks</span>
                        </div>
                        <div class="h-64">
                            <canvas id="newUsersWeekly"></canvas>
                        </div>
                    </div>

                    <!-- Monthly -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Monthly (Last 12 Months)</h3>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded-full">{{ count($newUsersMonthly['labels']) }}
                                months</span>
                        </div>
                        <div class="h-64">
                            <canvas id="newUsersMonthly"></canvas>
                        </div>
                    </div>

                    <!-- Yearly -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Yearly</h3>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded-full">{{ count($newUsersYearly['labels']) }}
                                years</span>
                        </div>
                        <div class="h-64">
                            <canvas id="newUsersYearly"></canvas>
                        </div>
                    </div>

                    <!-- All Time -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:col-span-2">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">All Time</h3>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded-full">{{ count($newUsersAllTime['labels']) }}
                                periods</span>
                        </div>
                        <div class="h-64">
                            <canvas id="newUsersAllTime"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Users Charts Section -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Active Users Analytics
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mx-4 sm:mx-6 lg:mx-8">
                    <!-- Daily -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Daily (Last 7 Days)</h3>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 rounded-full">{{ count($activeUsersDaily['labels']) }}
                                days</span>
                        </div>
                        <div class="h-64">
                            <canvas id="activeUsersDaily"></canvas>
                        </div>
                    </div>

                    <!-- Weekly -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Weekly (Last 4 Weeks)</h3>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 rounded-full">{{ count($activeUsersWeekly['labels']) }}
                                weeks</span>
                        </div>
                        <div class="h-64">
                            <canvas id="activeUsersWeekly"></canvas>
                        </div>
                    </div>

                    <!-- Monthly -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Monthly (Last 12 Months)</h3>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 rounded-full">{{ count($activeUsersMonthly['labels']) }}
                                months</span>
                        </div>
                        <div class="h-64">
                            <canvas id="activeUsersMonthly"></canvas>
                        </div>
                    </div>

                    <!-- Yearly -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Yearly</h3>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 rounded-full">{{ count($activeUsersYearly['labels']) }}
                                years</span>
                        </div>
                        <div class="h-64">
                            <canvas id="activeUsersYearly"></canvas>
                        </div>
                    </div>

                    <!-- All Time -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:col-span-2">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">All Time</h3>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 rounded-full">{{ count($activeUsersAllTime['labels']) }}
                                periods</span>
                        </div>
                        <div class="h-64">
                            <canvas id="activeUsersAllTime"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inactive Users Section -->
            <div class="mt-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Inactive Users Breakdown
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mx-4 sm:mx-6 lg:mx-8">
                    <!-- Inactive Users Chart -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Dormancy Categories</h3>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200 rounded-full">{{ count($inactiveUsersBreakdown['labels']) }}
                                categories</span>
                        </div>
                        <div class="h-80">
                            <canvas id="inactiveUsersBreakdown"></canvas>
                        </div>
                    </div>

                    <!-- Inactive Users Summary -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Summary Statistics</h3>
                        <div class="space-y-4">
                            @foreach($inactiveUsersBreakdown['labels'] as $index => $category)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-4 h-4 rounded-full"
                                            style="background-color: {{ $index === 0 ? '#ef4444' : ($index === 1 ? '#f97316' : '#dc2626') }}">
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-gray-100 capitalize">{{ $category }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ number_format($inactiveUsersBreakdown['values'][$index]) }}</span>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ round(($inactiveUsersBreakdown['values'][$index] / array_sum($inactiveUsersBreakdown['values'])) * 100, 1) }}%
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart.js configuration and theme management
    let charts = {};
    let isDarkMode = document.documentElement.classList.contains('dark');

    // Theme colors
    const colors = {
        light: {
            background: 'rgba(255, 255, 255, 0.8)',
            text: '#374151',
            grid: 'rgba(0, 0, 0, 0.1)',
            newUsers: {
                primary: '#2563eb',
                secondary: 'rgba(37, 99, 235, 0.1)'
            },
            activeUsers: {
                primary: '#16a34a',
                secondary: 'rgba(22, 163, 74, 0.1)'
            }
        },
        dark: {
            background: 'rgba(17, 24, 39, 0.95)',
            text: '#e5e7eb',
            grid: 'rgba(75, 85, 99, 0.3)',
            newUsers: {
                primary: '#60a5fa',
                secondary: 'rgba(96, 165, 250, 0.15)'
            },
            activeUsers: {
                primary: '#34d399',
                secondary: 'rgba(52, 211, 153, 0.15)'
            },
            inactiveUsers: {
                primary: ['#f87171', '#fb923c', '#f87171'],
                secondary: ['rgba(248, 113, 113, 0.15)', 'rgba(251, 146, 60, 0.15)', 'rgba(248, 113, 113, 0.15)']
            }
        }
    };

    function getThemeColors() {
        return isDarkMode ? colors.dark : colors.light;
    }

    // Chart creation functions
    function createChart(canvasId, data, type, colorScheme) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        const themeColors = getThemeColors();
        const chartColors = themeColors[colorScheme];

        const config = {
            type: type,
            data: {
                labels: data.labels,
                datasets: [{
                    label: colorScheme === 'newUsers' ? 'New Users' : 'Active Users',
                    data: data.values,
                    backgroundColor: type === 'bar' ? chartColors.secondary : chartColors.primary,
                    borderColor: chartColors.primary,
                    borderWidth: 2,
                    fill: type === 'line',
                    tension: 0.4,
                    pointBackgroundColor: chartColors.primary,
                    pointBorderColor: chartColors.primary,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: themeColors.background,
                        titleColor: themeColors.text,
                        bodyColor: themeColors.text,
                        borderColor: chartColors.primary,
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: themeColors.text,
                            font: { size: 11 }
                        },
                        grid: {
                            color: themeColors.grid,
                            drawBorder: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: themeColors.text,
                            font: { size: 11 }
                        },
                        grid: {
                            color: themeColors.grid,
                            drawBorder: false
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        };

        return new Chart(ctx, config);
    }

    function destroyAllCharts() {
        Object.values(charts).forEach(chart => chart?.destroy());
        charts = {};
    }

    // Special function for inactive users doughnut chart
    function createInactiveUsersChart(canvasId, data) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        const themeColors = getThemeColors();
        const colors = ['#ef4444', '#f97316', '#dc2626'];
        const backgroundColors = ['rgba(239, 68, 68, 0.8)', 'rgba(249, 115, 22, 0.8)', 'rgba(220, 38, 38, 0.8)'];

        const config = {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Inactive Users',
                    data: data.values,
                    backgroundColor: backgroundColors,
                    borderColor: colors,
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: themeColors.text,
                            font: { size: 12 },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: themeColors.background,
                        titleColor: themeColors.text,
                        bodyColor: themeColors.text,
                        borderColor: colors[0],
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        };

        return new Chart(ctx, config);
    }

    function createAllCharts() {
        // New Users Charts
        charts.newUsersDaily = createChart('newUsersDaily', @json($newUsersDaily), 'bar', 'newUsers');
        charts.newUsersWeekly = createChart('newUsersWeekly', @json($newUsersWeekly), 'line', 'newUsers');
        charts.newUsersMonthly = createChart('newUsersMonthly', @json($newUsersMonthly), 'line', 'newUsers');
        charts.newUsersYearly = createChart('newUsersYearly', @json($newUsersYearly), 'bar', 'newUsers');
        charts.newUsersAllTime = createChart('newUsersAllTime', @json($newUsersAllTime), 'line', 'newUsers');

        // Active Users Charts
        charts.activeUsersDaily = createChart('activeUsersDaily', @json($activeUsersDaily), 'bar', 'activeUsers');
        charts.activeUsersWeekly = createChart('activeUsersWeekly', @json($activeUsersWeekly), 'line', 'activeUsers');
        charts.activeUsersMonthly = createChart('activeUsersMonthly', @json($activeUsersMonthly), 'line', 'activeUsers');
        charts.activeUsersYearly = createChart('activeUsersYearly', @json($activeUsersYearly), 'bar', 'activeUsers');
        charts.activeUsersAllTime = createChart('activeUsersAllTime', @json($activeUsersAllTime), 'line', 'activeUsers');

        // Inactive Users Chart
        charts.inactiveUsersBreakdown = createInactiveUsersChart('inactiveUsersBreakdown', @json($inactiveUsersBreakdown));
    }

    // Theme toggle functionality
    function toggleTheme() {
        document.documentElement.classList.toggle('dark');
        isDarkMode = !isDarkMode;

        // Update icons
        document.getElementById('moonIcon').classList.toggle('hidden');
        document.getElementById('sunIcon').classList.toggle('hidden');

        // Recreate charts with new theme
        destroyAllCharts();
        setTimeout(createAllCharts, 100);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function () {
        // Theme toggle
        document.getElementById('themeToggle').addEventListener('click', toggleTheme);

        // Always attempt to create charts after a short delay
        setTimeout(() => {
            if (!@json($loading) && !@json($error)) {
                createAllCharts();
            }
        }, 500);
    });

    // Handle Livewire updates
    document.addEventListener('livewire:init', () => {
        Livewire.on('dashboardUpdated', () => {
            setTimeout(() => {
                destroyAllCharts();
                createAllCharts();
            }, 100);
        });
    });
</script>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Enhanced Modern Header -->
    <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200/20 dark:border-gray-700/30 transition-colors duration-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo & Title -->
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                        </div>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">TradingPro Analytics</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">User Analytics Dashboard</p>
                    </div>
                </div>

                <!-- Enhanced Navigation -->
                <div class="flex items-center space-x-6">
                    <nav class="hidden md:flex items-center space-x-2 bg-gray-50/50 dark:bg-gray-800/50 rounded-full p-1 border border-gray-200/50 dark:border-gray-700/50">
                        <a href="{{ route('dashboard') }}"
                            class="px-6 py-2.5 text-sm font-medium rounded-full text-gray-700 hover:text-gray-900 hover:bg-white/60 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700/60 transition-all duration-300 hover:shadow-md backdrop-blur-sm">
                            Dashboard
                        </a>
                        <a href="{{ route('user-analytics') }}"
                            class="relative px-6 py-2.5 text-sm font-semibold rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg shadow-blue-500/25 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/30 hover:scale-105">
                            <span class="relative z-10">User Analytics</span>
                        </a>
                        <a href="{{ route('transactions') }}"
                            class="px-6 py-2.5 text-sm font-medium rounded-full text-gray-700 hover:text-gray-900 hover:bg-white/60 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700/60 transition-all duration-300 hover:shadow-md backdrop-blur-sm">
                            Transactions
                        </a>
                    </nav>

                    <!-- Theme Toggle with enhanced design -->
                    <div class="flex items-center space-x-3">
                        <button id="themeToggle"
                            class="relative p-3 rounded-full bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 hover:shadow-lg transition-all duration-300 hover:scale-110 group">
                            <svg id="moonIcon" class="w-5 h-5 text-gray-600 dark:text-gray-300 transition-transform duration-300 group-hover:rotate-12" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.017 8.017 0 1010.586 10.586z"></path>
                            </svg>
                            <svg id="sunIcon" class="hidden w-5 h-5 text-gray-600 dark:text-gray-300 transition-transform duration-300 group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <!-- Refresh Button -->
                        <button wire:click="refreshData" wire:loading.attr="disabled"
                            class="flex items-center space-x-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 disabled:opacity-50">
                            <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <div wire:loading
                                class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            <span wire:loading.remove>Refresh</span>
                            <span wire:loading>Syncing...</span>
                        </button>

                        <!-- Mobile menu button -->
                        <button id="mobileMenuToggle" class="md:hidden p-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-200">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobileMenu" class="md:hidden hidden border-t border-gray-200/50 dark:border-gray-700/50 pt-4 pb-6">
                <nav class="flex flex-col space-y-2">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 transition-colors duration-200">
                        Dashboard
                    </a>
                    <a href="{{ route('user-analytics') }}"
                        class="px-4 py-3 text-sm font-semibold rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg">
                        User Analytics
                    </a>
                    <a href="{{ route('transactions') }}"
                        class="px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 transition-colors duration-200">
                        Transactions
                    </a>
                </nav>
            </div>
        </div>
    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Error Display -->
        @if($error)
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-400 dark:border-red-500 rounded-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 dark:text-red-300 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Error Loading Data</h3>
                        <p class="text-sm text-red-700 dark:text-red-300 mt-1">{{ $error }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Success Message -->
        @if(session()->has('message'))
            <div
                class="mb-6 p-4 bg-green-50 dark:bg-green-900/50 border-l-4 border-green-400 dark:border-green-500 rounded-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400 dark:text-green-300 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800 dark:text-green-200">Success</h3>
                        <p class="text-sm text-green-700 dark:text-green-300 mt-1">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($loading)
            <!-- Loading State -->
            <div class="flex justify-center items-center py-32">
                <div class="text-center">
                    <div
                        class="w-12 h-12 border-4 border-gray-200 dark:border-gray-700 border-t-blue-600 dark:border-t-blue-400 rounded-full animate-spin mx-auto">
                    </div>
                    <p class="mt-4 text-lg font-medium text-gray-700 dark:text-gray-300">Loading user analytics...</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">This may take a moment for large datasets</p>
                </div>
            </div>
        @else
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <!-- Active Users Summary -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900/50 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Users</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ number_format($activeUsers->total_count ?? 0) }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">With first deposit & recent activity
                            </p>
                        </div>
                    </div>
                </div>

                <!-- New Users Summary -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">New Users</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ number_format($newUsers->total_count ?? 0) }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">With first deposit</p>
                        </div>
                    </div>
                </div>

                <!-- Inactive Users Summary -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 dark:bg-red-900/50 rounded-lg">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Inactive Users</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ number_format($inactiveUsers->total_count ?? 0) }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">With first deposit but inactive</p>
                            @if($inactiveUsers && isset($inactiveUsers->metadata['trading_status_breakdown']))
                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    @php
                                        $breakdown = $inactiveUsers->metadata['trading_status_breakdown'];
                                        $topStatus = collect($breakdown)->sortDesc()->first();
                                        $topStatusName = collect($breakdown)->sortDesc()->keys()->first();
                                    @endphp
                                    Top: {{ ucfirst(str_replace(['dormant ', 'more than '], ['', '1+ '], $topStatusName)) }}
                                    ({{ number_format($topStatus) }})
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Sections -->
            <div class="space-y-12">
                <!-- Active Users Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Active Users Analytics</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Users with firstDepositDate who logged in today
                            - All Time</p>
                    </div>
                    <div class="h-80">
                        <canvas id="activeUsersChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- New Users Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">New Users Analytics</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">New registrations with firstDepositDate based on
                            registration date</p>
                    </div>

                    <!-- Weekly Chart -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3">Weekly (Last 7 Days)</h4>
                        <div class="h-64">
                            <canvas id="newUsersWeeklyChart" width="400" height="160"></canvas>
                        </div>
                    </div>

                    <!-- Monthly Chart -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3">Monthly (Current Month)</h4>
                        <div class="h-64">
                            <canvas id="newUsersMonthlyChart" width="400" height="160"></canvas>
                        </div>
                    </div>

                    <!-- Each Month Chart -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3">Each Month (Last 12 Months)
                        </h4>
                        <div class="h-64">
                            <canvas id="newUsersEachMonthChart" width="400" height="160"></canvas>
                        </div>
                    </div>

                    <!-- Yearly Chart -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3">Yearly (Current Year)</h4>
                        <div class="h-64">
                            <canvas id="newUsersYearlyChart" width="400" height="160"></canvas>
                        </div>
                    </div>

                    <!-- Each Year Chart -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3">Each Year (All Years)</h4>
                        <div class="h-64">
                            <canvas id="newUsersEachYearChart" width="400" height="160"></canvas>
                        </div>
                    </div>

                    <!-- All Time Chart -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3">All Time (Y-Axis Max: 500)
                        </h4>
                        <div class="h-64">
                            <canvas id="newUsersAllTimeChart" width="400" height="160"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Inactive Users Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Inactive Users Analytics</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Users with firstDepositDate but inactive based
                            on last login date - All Time</p>
                    </div>

                    <!-- Timeline Chart -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3">Inactive Users Timeline</h4>
                        <div class="h-80">
                            <canvas id="inactiveUsersChart" width="400" height="200"></canvas>
                        </div>
                    </div>

                    <!-- Status Breakdown Chart -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3">Inactive Users by Trading
                            Status</h4>
                        <div class="h-80">
                            <canvas id="inactiveUsersStatusChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let activeUsersChart, inactiveUsersChart, inactiveUsersStatusChart;
        let newUsersWeeklyChart, newUsersMonthlyChart, newUsersEachMonthChart,
            newUsersYearlyChart, newUsersEachYearChart, newUsersAllTimeChart;

        document.addEventListener('DOMContentLoaded', function () {
            initCharts();
        });

        document.addEventListener('livewire:updated', function () {
            updateCharts();
        });

        function initCharts() {
            // Active Users Chart
            const activeCtx = document.getElementById('activeUsersChart').getContext('2d');
            activeUsersChart = new Chart(activeCtx, {
                type: 'line',
                data: {
                    labels: @js($activeUsersChartData['labels'] ?? []),
                    datasets: [{
                        label: 'Active Users',
                        data: @js($activeUsersChartData['data'] ?? []),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Inactive Users Chart
            const inactiveCtx = document.getElementById('inactiveUsersChart').getContext('2d');
            inactiveUsersChart = new Chart(inactiveCtx, {
                type: 'line',
                data: {
                    labels: @js($inactiveUsersChartData['labels'] ?? []),
                    datasets: [{
                        label: 'Inactive Users',
                        data: @js($inactiveUsersChartData['data'] ?? []),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Inactive Users Status Breakdown Chart
            const inactiveStatusCtx = document.getElementById('inactiveUsersStatusChart').getContext('2d');
            inactiveUsersStatusChart = new Chart(inactiveStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: @js($inactiveUsersStatusBreakdownData['labels'] ?? []),
                    datasets: [{
                        label: 'Inactive Users by Status',
                        data: @js($inactiveUsersStatusBreakdownData['data'] ?? []),
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.8)',   // Red
                            'rgba(245, 158, 11, 0.8)',  // Orange
                            'rgba(168, 85, 247, 0.8)',  // Purple
                            'rgba(59, 130, 246, 0.8)',  // Blue
                            'rgba(16, 185, 129, 0.8)',  // Green
                            'rgba(156, 163, 175, 0.8)', // Gray
                        ],
                        borderColor: [
                            'rgb(239, 68, 68)',
                            'rgb(245, 158, 11)',
                            'rgb(168, 85, 247)',
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(156, 163, 175)',
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });

            // New Users Weekly Chart
            const weeklyCtx = document.getElementById('newUsersWeeklyChart').getContext('2d');
            newUsersWeeklyChart = new Chart(weeklyCtx, {
                type: 'bar',
                data: {
                    labels: @js($newUsersWeeklyChartData['labels'] ?? []),
                    datasets: [{
                        label: 'New Users (Weekly)',
                        data: @js($newUsersWeeklyChartData['data'] ?? []),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // New Users Monthly Chart
            const monthlyCtx = document.getElementById('newUsersMonthlyChart').getContext('2d');
            newUsersMonthlyChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: @js($newUsersMonthlyChartData['labels'] ?? []),
                    datasets: [{
                        label: 'New Users (Monthly)',
                        data: @js($newUsersMonthlyChartData['data'] ?? []),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // New Users Each Month Chart
            const eachMonthCtx = document.getElementById('newUsersEachMonthChart').getContext('2d');
            newUsersEachMonthChart = new Chart(eachMonthCtx, {
                type: 'bar',
                data: {
                    labels: @js($newUsersEachMonthChartData['labels'] ?? []),
                    datasets: [{
                        label: 'New Users (Each Month)',
                        data: @js($newUsersEachMonthChartData['data'] ?? []),
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // New Users Yearly Chart
            const yearlyCtx = document.getElementById('newUsersYearlyChart').getContext('2d');
            newUsersYearlyChart = new Chart(yearlyCtx, {
                type: 'line',
                data: {
                    labels: @js($newUsersYearlyChartData['labels'] ?? []),
                    datasets: [{
                        label: 'New Users (Yearly)',
                        data: @js($newUsersYearlyChartData['data'] ?? []),
                        borderColor: 'rgb(168, 85, 247)',
                        backgroundColor: 'rgba(168, 85, 247, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // New Users Each Year Chart
            const eachYearCtx = document.getElementById('newUsersEachYearChart').getContext('2d');
            newUsersEachYearChart = new Chart(eachYearCtx, {
                type: 'bar',
                data: {
                    labels: @js($newUsersEachYearChartData['labels'] ?? []),
                    datasets: [{
                        label: 'New Users (Each Year)',
                        data: @js($newUsersEachYearChartData['data'] ?? []),
                        backgroundColor: 'rgba(245, 158, 11, 0.8)',
                        borderColor: 'rgb(245, 158, 11)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // New Users All Time Chart
            const allTimeCtx = document.getElementById('newUsersAllTimeChart').getContext('2d');
            newUsersAllTimeChart = new Chart(allTimeCtx, {
                type: 'line',
                data: {
                    labels: @js($newUsersAllTimeChartData['labels'] ?? []),
                    datasets: [{
                        label: 'New Users (All Time)',
                        data: @js($newUsersAllTimeChartData['data'] ?? []),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 500
                        }
                    }
                }
            });
        }

        function updateCharts() {
            if (activeUsersChart) {
                activeUsersChart.data.labels = @js($activeUsersChartData['labels'] ?? []);
                activeUsersChart.data.datasets[0].data = @js($activeUsersChartData['data'] ?? []);
                activeUsersChart.update();
            }

            if (inactiveUsersChart) {
                inactiveUsersChart.data.labels = @js($inactiveUsersChartData['labels'] ?? []);
                inactiveUsersChart.data.datasets[0].data = @js($inactiveUsersChartData['data'] ?? []);
                inactiveUsersChart.update();
            }

            if (inactiveUsersStatusChart) {
                inactiveUsersStatusChart.data.labels = @js($inactiveUsersStatusBreakdownData['labels'] ?? []);
                inactiveUsersStatusChart.data.datasets[0].data = @js($inactiveUsersStatusBreakdownData['data'] ?? []);
                inactiveUsersStatusChart.update();
            }

            if (newUsersWeeklyChart) {
                newUsersWeeklyChart.data.labels = @js($newUsersWeeklyChartData['labels'] ?? []);
                newUsersWeeklyChart.data.datasets[0].data = @js($newUsersWeeklyChartData['data'] ?? []);
                newUsersWeeklyChart.update();
            }

            if (newUsersMonthlyChart) {
                newUsersMonthlyChart.data.labels = @js($newUsersMonthlyChartData['labels'] ?? []);
                newUsersMonthlyChart.data.datasets[0].data = @js($newUsersMonthlyChartData['data'] ?? []);
                newUsersMonthlyChart.update();
            }

            if (newUsersEachMonthChart) {
                newUsersEachMonthChart.data.labels = @js($newUsersEachMonthChartData['labels'] ?? []);
                newUsersEachMonthChart.data.datasets[0].data = @js($newUsersEachMonthChartData['data'] ?? []);
                newUsersEachMonthChart.update();
            }

            if (newUsersYearlyChart) {
                newUsersYearlyChart.data.labels = @js($newUsersYearlyChartData['labels'] ?? []);
                newUsersYearlyChart.data.datasets[0].data = @js($newUsersYearlyChartData['data'] ?? []);
                newUsersYearlyChart.update();
            }

            if (newUsersEachYearChart) {
                newUsersEachYearChart.data.labels = @js($newUsersEachYearChartData['labels'] ?? []);
                newUsersEachYearChart.data.datasets[0].data = @js($newUsersEachYearChartData['data'] ?? []);
                newUsersEachYearChart.update();
            }

            if (newUsersAllTimeChart) {
                newUsersAllTimeChart.data.labels = @js($newUsersAllTimeChartData['labels'] ?? []);
                newUsersAllTimeChart.data.datasets[0].data = @js($newUsersAllTimeChartData['data'] ?? []);
                newUsersAllTimeChart.update();
            }
        }

        // Enhanced Theme Toggle functionality
        const themeToggle = document.getElementById('themeToggle');
        const moonIcon = document.getElementById('moonIcon');
        const sunIcon = document.getElementById('sunIcon');
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        // Theme Toggle
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                if (moonIcon && sunIcon) {
                    moonIcon.classList.toggle('hidden');
                    sunIcon.classList.toggle('hidden');
                }

                // Save preference
                if (document.documentElement.classList.contains('dark')) {
                    localStorage.setItem('theme', 'dark');
                } else {
                    localStorage.setItem('theme', 'light');
                }
            });
        }

        // Mobile Menu Toggle
        if (mobileMenuToggle && mobileMenu) {
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
        }

        // Load saved theme
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            if (moonIcon && sunIcon) {
                moonIcon.classList.add('hidden');
                sunIcon.classList.remove('hidden');
            }
        }
    </script>
</div>

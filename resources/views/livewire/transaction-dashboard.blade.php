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
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                    </div>
                    <div>
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            TradingPro Analytics</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Transaction Analytics Dashboard
                        </p>
                    </div>
                </div>

                <!-- Enhanced Navigation -->
                <div class="flex items-center space-x-6">
                    <nav
                        class="hidden md:flex items-center space-x-2 bg-gray-50/50 dark:bg-gray-800/50 rounded-full p-1 border border-gray-200/50 dark:border-gray-700/50">
                        <a href="{{ route('dashboard') }}"
                            class="px-6 py-2.5 text-sm font-medium rounded-full text-gray-700 hover:text-gray-900 hover:bg-white/60 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700/60 transition-all duration-300 hover:shadow-md backdrop-blur-sm">
                            Dashboard
                        </a>
                        <a href="{{ route('user-analytics') }}"
                            class="px-6 py-2.5 text-sm font-medium rounded-full text-gray-700 hover:text-gray-900 hover:bg-white/60 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700/60 transition-all duration-300 hover:shadow-md backdrop-blur-sm">
                            User Analytics
                        </a>
                        <a href="{{ route('transactions') }}"
                            class="relative px-6 py-2.5 text-sm font-semibold rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg shadow-blue-500/25 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/30 hover:scale-105">
                            <span class="relative z-10">Transactions</span>
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
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 transition-colors duration-200">
                        Dashboard
                    </a>
                    <a href="{{ route('user-analytics') }}"
                        class="px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 transition-colors duration-200">
                        User Analytics
                    </a>
                    <a href="{{ route('transactions') }}"
                        class="px-4 py-3 text-sm font-semibold rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg">
                        Transactions
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Controls Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-center space-x-4">
            <!-- Period Selector -->
            <div class="flex items-center space-x-3">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Period:</label>
                <select wire:model.live="selectedPeriod"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200">
                    <option value="all_time">All Time</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>

            <!-- Toggle Top Transactions -->
            <button wire:click="toggleTopTransactions"
                class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                               {{ $showTopTransactions ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 ring-2 ring-blue-200 dark:ring-blue-800' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                <span>Top 10</span>
            </button>

            <!-- Refresh Button -->
            <button wire:click="refresh"
                class="flex items-center space-x-2 px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Refresh</span>
            </button>
        </div>
    </div>
</div>
</div>

<!-- Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Flash Messages -->
    @if(session('sync_needed'))
        <div
            class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/50 border-l-4 border-yellow-400 dark:border-yellow-500 rounded-lg">
            <div class="flex">
                <svg class="w-5 h-5 text-yellow-400 dark:text-yellow-300 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Data Sync Needed</h3>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">{{ session('sync_needed') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('message'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/50 border-l-4 border-green-400 dark:border-green-500 rounded-lg">
            <div class="flex">
                <svg class="w-5 h-5 text-green-400 dark:text-green-300 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <p class="text-sm text-green-700 dark:text-green-300">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Summary Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Approved Deposits -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved Deposits</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ number_format($this->summaryStats['deposit']['approved']['total_count'] ?? 0) }}
                    </p>
                    <p class="text-sm text-green-600 dark:text-green-400">
                        ${{ number_format($this->summaryStats['deposit']['approved']['total_amount'] ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Declined Deposits -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Declined Deposits</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ number_format($this->summaryStats['deposit']['declined']['total_count'] ?? 0) }}
                    </p>
                    <p class="text-sm text-red-600 dark:text-red-400">
                        ${{ number_format($this->summaryStats['deposit']['declined']['total_amount'] ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Approved Withdrawals -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 12H4m16 0l-4 4m4-4l-4-4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved Withdrawals</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ number_format($this->summaryStats['withdrawal']['approved']['total_count'] ?? 0) }}
                    </p>
                    <p class="text-sm text-blue-600 dark:text-blue-400">
                        ${{ number_format($this->summaryStats['withdrawal']['approved']['total_amount'] ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Declined Withdrawals -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 dark:bg-orange-900/50 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Declined Withdrawals</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ number_format($this->summaryStats['withdrawal']['declined']['total_count'] ?? 0) }}
                    </p>
                    <p class="text-sm text-orange-600 dark:text-orange-400">
                        ${{ number_format($this->summaryStats['withdrawal']['declined']['total_amount'] ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Withdrawal/Deposit Ratio -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">W/D Ratio</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $this->withdrawalDepositRatio }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Withdrawals to Deposits</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        @foreach(['approved', 'declined'] as $status)
            @foreach(['deposit', 'withdrawal'] as $type)
                @php
                    $key = "{$type}_{$status}";
                    $data = $this->chartData[$key] ?? null;
                    $colors = [
                        'deposit_approved' => ['bg' => 'bg-green-50 dark:bg-green-900/20', 'text' => 'text-green-600 dark:text-green-400', 'border' => 'border-green-200 dark:border-green-800'],
                        'deposit_declined' => ['bg' => 'bg-red-50 dark:bg-red-900/20', 'text' => 'text-red-600 dark:text-red-400', 'border' => 'border-red-200 dark:border-red-800'],
                        'withdrawal_approved' => ['bg' => 'bg-blue-50 dark:bg-blue-900/20', 'text' => 'text-blue-600 dark:text-blue-400', 'border' => 'border-blue-200 dark:border-blue-800'],
                        'withdrawal_declined' => ['bg' => 'bg-orange-50 dark:bg-orange-900/20', 'text' => 'text-orange-600 dark:text-orange-400', 'border' => 'border-orange-200 dark:border-orange-800'],
                    ];
                @endphp

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 {{ $colors[$key]['bg'] }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 capitalize">
                                    {{ ucfirst($status) }} {{ ucfirst($type) }}s
                                </h3>
                                @if($data)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Total: {{ number_format($data['total_count']) }} transactions |
                                        ${{ number_format($data['total_amount'], 2) }}
                                    </p>
                                @endif
                            </div>
                            <div class="p-2 {{ $colors[$key]['bg'] }} rounded-lg {{ $colors[$key]['border'] }} border">
                                <svg class="w-5 h-5 {{ $colors[$key]['text'] }}" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    @if($type === 'deposit')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4m16 0l-4 4m4-4l-4-4" />
                                    @endif
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($data && $data['chart_data'])
                            <canvas id="chart-{{ $key }}" class="w-full h-64"></canvas>
                        @else
                            <div class="flex items-center justify-center h-64 text-gray-500 dark:text-gray-400">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <p class="text-sm">No data available</p>
                                    <p class="text-xs mt-1">Run sync command to populate data</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>

    <!-- Top Transactions Section -->
    @if($showTopTransactions)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach(['approved', 'declined'] as $status)
                @foreach(['deposit', 'withdrawal'] as $type)
                    @php
                        $key = "{$type}_{$status}";
                        $data = $this->chartData[$key] ?? null;
                        $colors = [
                            'deposit_approved' => 'border-green-200 dark:border-green-800',
                            'deposit_declined' => 'border-red-200 dark:border-red-800',
                            'withdrawal_approved' => 'border-blue-200 dark:border-blue-800',
                            'withdrawal_declined' => 'border-orange-200 dark:border-orange-800',
                        ];
                    @endphp

                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Top 10 {{ ucfirst($status) }} {{ ucfirst($type) }}s
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($data && $data['top_transactions'])
                                <div class="space-y-3">
                                    @foreach($data['top_transactions'] as $index => $transaction)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                    <span
                                                        class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $index + 1 }}</span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        ID: {{ $transaction['id'] ?? 'N/A' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ \Carbon\Carbon::parse($transaction['created_at'])->format('M j, Y H:i') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                    ${{ number_format($transaction['amount'], 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">No top transactions data available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    @endif
</div>

<!-- Chart.js Integration -->
@script
<script>
    let charts = {};

    function initializeCharts() {
        const chartData = @json($this->chartData);
        const isDark = document.documentElement.classList.contains('dark');

        // Common chart colors
        const colors = {
            deposit_approved: {
                primary: '#059669',
                secondary: 'rgba(5, 150, 105, 0.1)',
                border: '#10b981'
            },
            deposit_declined: {
                primary: '#dc2626',
                secondary: 'rgba(220, 38, 38, 0.1)',
                border: '#ef4444'
            },
            withdrawal_approved: {
                primary: '#2563eb',
                secondary: 'rgba(37, 99, 235, 0.1)',
                border: '#3b82f6'
            },
            withdrawal_declined: {
                primary: '#ea580c',
                secondary: 'rgba(234, 88, 12, 0.1)',
                border: '#f97316'
            }
        };

        Object.keys(chartData).forEach(key => {
            const canvas = document.getElementById(`chart-${key}`);
            if (!canvas) return;

            const data = chartData[key];
            if (!data.chart_data || !data.chart_data.labels) return;

            // Destroy existing chart if it exists
            if (charts[key]) {
                charts[key].destroy();
            }

            const ctx = canvas.getContext('2d');
            const color = colors[key];

            charts[key] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.chart_data.labels,
                    datasets: [{
                        label: 'Transaction Count',
                        data: data.chart_data.datasets[0].data,
                        backgroundColor: color.secondary,
                        borderColor: color.border,
                        borderWidth: 2,
                        borderRadius: 4,
                        borderSkipped: false,
                    }, {
                        label: 'Transaction Amount ($)',
                        data: data.chart_data.datasets[1].data,
                        type: 'line',
                        borderColor: color.primary,
                        backgroundColor: 'transparent',
                        borderWidth: 3,
                        tension: 0.4,
                        pointBackgroundColor: color.primary,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        yAxisID: 'amount'
                    }
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: isDark ? '#e5e7eb' : '#374151',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#1f2937' : '#ffffff',
                            titleColor: isDark ? '#f3f4f6' : '#111827',
                            bodyColor: isDark ? '#d1d5db' : '#374151',
                            borderColor: isDark ? '#374151' : '#e5e7eb',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function (context) {
                                    if (context.dataset.label.includes('Amount')) {
                                        return context.dataset.label + ': $' +
                                            context.parsed.y.toLocaleString();
                                    }
                                    return context.dataset.label + ': ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: isDark ? '#374151' : '#f3f4f6',
                                borderColor: isDark ? '#6b7280' : '#d1d5db',
                            },
                            ticks: {
                                color: isDark ? '#d1d5db' : '#6b7280',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: {
                                color: isDark ? '#374151' : '#f3f4f6',
                                borderColor: isDark ? '#6b7280' : '#d1d5db',
                            },
                            ticks: {
                                color: isDark ? '#d1d5db' : '#6b7280',
                                font: {
                                    size: 11
                                },
                                callback: function (value) {
                                    return value.toLocaleString();
                                }
                            }
                        },
                        amount: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false,
                            },
                            ticks: {
                                color: isDark ? '#d1d5db' : '#6b7280',
                                font: {
                                    size: 11
                                },
                                callback: function (value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        });
    }

    // Initialize charts when component loads
    document.addEventListener('DOMContentLoaded', initializeCharts);

    // Reinitialize charts when period changes
    $wire.on('period-changed', () => {
        setTimeout(initializeCharts, 100);
    });

    // Reinitialize charts when data refreshes
    $wire.on('refresh-charts', () => {
        setTimeout(initializeCharts, 100);
    });

    // Handle theme changes
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.attributeName === 'class') {
                setTimeout(initializeCharts, 100);
            }
        });
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'
    });

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
@endscript
</div>
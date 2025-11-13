<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Modern Header -->
    <div
        class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
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
                        <p class="text-sm text-gray-500 dark:text-gray-400">Real-time CRM insights</p>
                    </div>
                </div>

                <!-- Controls -->
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

                    <!-- Time Period Selector -->
                    <div class="flex items-center space-x-3">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Period:</label>
                        <select wire:model.live="timePeriod"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200">
                            <option value="all">All Time</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>

                    <!-- Auto-scale Toggle -->
                    <button wire:click="toggleAutoScale"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                                   {{ $autoScale ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 ring-2 ring-blue-200 dark:ring-blue-800' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                        <span>Auto Scale</span>
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

        <!-- Loading State -->
        @if($loading)
            <div class="flex justify-center items-center py-32">
                <div class="text-center">
                    <div
                        class="w-12 h-12 border-4 border-gray-200 dark:border-gray-700 border-t-blue-600 dark:border-t-blue-400 rounded-full animate-spin mx-auto">
                    </div>
                    <p class="mt-4 text-lg font-medium text-gray-700 dark:text-gray-300">Loading analytics data...</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">This may take a moment for large datasets</p>

                    <!-- Progress indicators -->
                    @if(!empty($loadingProgress))
                        <div class="mt-6 space-y-2 max-w-xs mx-auto">
                            @foreach($loadingProgress as $task => $status)
                                    <div
                                        class="flex items-center justify-between text-sm bg-white dark:bg-gray-800 rounded-lg px-3 py-2 shadow border border-gray-200 dark:border-gray-700">
                                        <span
                                            class="text-gray-600 dark:text-gray-300 capitalize">{{ str_replace('_', ' ', $task) }}</span>
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium
                                                        {{ $status === 'Complete' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' :
                                ($status === 'Failed' || $status === 'Error' ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200' : 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200') }}">
                                            {{ $status }}
                                        </span>
                                    </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm dark:shadow-gray-900/20 border border-gray-200 dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-900/30 transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ number_format(($newUsersData['total_count'] ?? 0) + ($activeUsersData['total_count'] ?? 0) + ($inactiveUsersData['total_count'] ?? 0)) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- New Users -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm dark:shadow-gray-900/20 border border-gray-200 dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-900/30 transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900/50 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">New Users</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ number_format($newUsersData['total_count'] ?? 0) }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Registrations</p>
                        </div>
                    </div>
                </div>

                <!-- Active Users -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm dark:shadow-gray-900/20 border border-gray-200 dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-900/30 transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Users</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ number_format($activeUsersData['total_count'] ?? 0) }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Recent activity</p>
                        </div>
                    </div>
                </div>

                <!-- Inactive Users -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm dark:shadow-gray-900/20 border border-gray-200 dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-900/30 transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 dark:bg-orange-900/50 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Inactive Users</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ number_format($inactiveUsersData['total_count'] ?? 0) }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Dormant accounts</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- New Users Chart -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm dark:shadow-gray-900/20 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">New Users Registration</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">User registrations over time</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                                {{ number_format($newUsersData['total_count'] ?? 0) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total</div>
                        </div>
                    </div>

                    @if(!empty($newUsersData['chart_data']))
                        <div class="h-96 relative p-4">
                            <canvas id="newUsersChart" class="w-full h-full"></canvas>
                        </div>
                    @else
                        <div class="h-80 flex items-center justify-center">
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No data available</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Run analytics sync to populate data</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Active Users Chart -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm dark:shadow-gray-900/20 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Active Users Activity</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Last login activity patterns</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                {{ number_format($activeUsersData['total_count'] ?? 0) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Active</div>
                        </div>
                    </div>

                    @if(!empty($activeUsersData['chart_data']))
                        <div class="h-96 relative p-4">
                            <canvas id="activeUsersChart" class="w-full h-full"></canvas>
                        </div>
                    @else
                        <div class="h-80 flex items-center justify-center">
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No data available</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Run analytics sync to populate data</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Inactive Users Chart (Full Width) -->
                <div
                    class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm dark:shadow-gray-900/20 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Inactive Users Distribution</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Users segmented by dormancy period</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-orange-600 dark:text-orange-400">
                                {{ number_format($inactiveUsersData['total_count'] ?? 0) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Inactive</div>
                        </div>
                    </div>

                    @if(!empty($inactiveUsersData['chart_data']))
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <div class="lg:col-span-2">
                                <div class="h-96 relative p-4">
                                    <canvas id="inactiveUsersChart" class="w-full h-full"></canvas>
                                </div>
                            </div>

                            <!-- Breakdown Stats -->
                            @if(isset($inactiveUsersData['breakdown']))
                                <div class="space-y-4">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Dormancy Breakdown</h4>

                                    <div class="space-y-3">
                                        <div
                                            class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg">
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-white">3-6 Months</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">Recently inactive</div>
                                            </div>
                                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                                {{ number_format($inactiveUsersData['breakdown']['dormant_3_6_months']) }}
                                            </div>
                                        </div>

                                        <div
                                            class="flex items-center justify-between p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 rounded-lg">
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-white">6-12 Months</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">Moderately inactive</div>
                                            </div>
                                            <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                                                {{ number_format($inactiveUsersData['breakdown']['dormant_6_12_months']) }}
                                            </div>
                                        </div>

                                        <div
                                            class="flex items-center justify-between p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg">
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-white">1+ Years</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">Long-term inactive</div>
                                            </div>
                                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                                                {{ number_format($inactiveUsersData['breakdown']['dormant_1_year_plus']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="h-80 flex items-center justify-center">
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No data available</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Run analytics sync to populate data</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer Info -->
            <div class="mt-12 text-center">
                <div
                    class="inline-flex items-center space-x-2 px-4 py-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-400">
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Data updates automatically every hour • Last sync:
                        @if(isset($newUsersData['cached_at']))
                            {{ \Carbon\Carbon::parse($newUsersData['cached_at'])->diffForHumans() }}
                        @else
                            Never
                        @endif
                    </span>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
@endpush

@if(!$loading && !$error)
    <script wire:key="charts-{{ $timePeriod }}-{{ $autoScale ? 'auto' : 'fixed' }}">
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Initializing modern charts...');

            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded!');
                return;
            }

            let charts = {};
            let isDestroyed = false;
            const autoScale = @json($autoScale);

            // Modern chart defaults
            Chart.defaults.font.family = "'Inter', 'system-ui', 'sans-serif'";
            Chart.defaults.color = '#64748b';
            Chart.defaults.borderColor = '#e2e8f0';

            // Cleanup function to be called before component destruction
            function cleanupCharts() {
                isDestroyed = true;
                Object.entries(charts).forEach(([key, chart]) => {
                    if (chart && typeof chart.destroy === 'function') {
                        try {
                            // Check if canvas still exists in DOM
                            const canvas = document.getElementById(key);
                            if (canvas && canvas.ownerDocument) {
                                chart.destroy();
                            }
                        } catch (e) {
                            console.warn(`Error destroying chart ${key}:`, e);
                        }
                    }
                });
                charts = {};
            }

            function destroyExistingCharts() {
                if (isDestroyed) return;

                Object.entries(charts).forEach(([key, chart]) => {
                    if (chart && typeof chart.destroy === 'function') {
                        try {
                            // Verify canvas exists and is connected to DOM
                            const canvas = document.getElementById(key);
                            if (canvas && canvas.ownerDocument && canvas.isConnected) {
                                chart.destroy();
                            }
                        } catch (e) {
                            console.warn(`Error destroying chart ${key}:`, e);
                        }
                    }
                });
                charts = {};
            }

            function getMaxValue(data) {
                if (!data || data.length === 0) return 0;
                const max = Math.max(...data);
                return Math.ceil(max * 1.1); // Add 10% padding
            }

            function createGradient(ctx, color) {
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, color + '40');
                gradient.addColorStop(1, color + '00');
                return gradient;
            }

            function isCanvasValid(canvas) {
                return canvas &&
                    canvas.ownerDocument &&
                    canvas.isConnected &&
                    canvas.offsetWidth > 0 &&
                    canvas.offsetHeight > 0;
            }

            function createLineChart(canvasId, labels, data, label, primaryColor, secondaryColor = null) {
                if (isDestroyed) {
                    console.log(`Chart creation cancelled - component destroyed: ${canvasId}`);
                    return;
                }

                const canvas = document.getElementById(canvasId);
                if (!canvas) {
                    console.log(`Canvas not found: ${canvasId}`);
                    return;
                }

                if (!isCanvasValid(canvas)) {
                    console.warn(`Canvas ${canvasId} is not valid or not ready, delaying chart creation`);
                    setTimeout(() => {
                        if (!isDestroyed) {
                            createLineChart(canvasId, labels, data, label, primaryColor, secondaryColor);
                        }
                    }, 100);
                    return;
                }

                // Get the 2D rendering context
                const ctx = canvas.getContext('2d');
                if (!ctx) {
                    console.error(`Could not get 2D context for canvas: ${canvasId}`);
                    return;
                }

                if (!labels || !data || labels.length === 0 || data.length === 0) {
                    console.log(`No data for chart: ${canvasId}`, { labels, data });
                    return;
                }

                // Destroy existing chart if it exists
                if (charts[canvasId]) {
                    try {
                        charts[canvasId].destroy();
                    } catch (e) {
                        console.warn(`Error destroying chart ${canvasId}:`, e);
                    }
                }

                const maxValue = autoScale ? getMaxValue(data) : undefined;
                const gradient = createGradient(ctx, primaryColor);

                console.log(`Creating line chart ${canvasId} with ${labels.length} data points, max: ${maxValue}`);

                try {
                    charts[canvasId] = new Chart(canvas, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: label,
                                data: data,
                                borderColor: primaryColor,
                                backgroundColor: gradient,
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: primaryColor,
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointHoverBackgroundColor: primaryColor,
                                pointHoverBorderColor: '#ffffff',
                                pointHoverBorderWidth: 3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            resizeDelay: 200,
                            layout: {
                                padding: {
                                    bottom: 40,
                                    left: 20,
                                    right: 20,
                                    top: 20
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: maxValue,
                                    grid: {
                                        color: '#f1f5f9',
                                        borderColor: '#e2e8f0'
                                    },
                                    ticks: {
                                        stepSize: autoScale ? Math.ceil(maxValue / 5) : undefined,
                                        color: '#64748b',
                                        font: {
                                            size: 12,
                                            weight: '500'
                                        },
                                        padding: 10
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        maxRotation: 45,
                                        minRotation: 0,
                                        color: '#64748b',
                                        font: {
                                            size: 10,
                                            weight: '500'
                                        },
                                        maxTicksLimit: 12,
                                        padding: 15,
                                        callback: function (value, index, ticks) {
                                            const label = this.getLabelForValue(value);
                                            // Show every nth label to prevent crowding
                                            const showEvery = Math.max(1, Math.floor(ticks.length / 8));
                                            return index % showEvery === 0 ? label : '';
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                                    titleColor: '#ffffff',
                                    bodyColor: '#ffffff',
                                    cornerRadius: 12,
                                    padding: 12,
                                    displayColors: false,
                                    titleFont: {
                                        size: 13,
                                        weight: '600'
                                    },
                                    bodyFont: {
                                        size: 14,
                                        weight: '500'
                                    },
                                    callbacks: {
                                        label: function (context) {
                                            return `${label}: ${context.parsed.y.toLocaleString()}`;
                                        }
                                    }
                                }
                            },
                            elements: {
                                point: {
                                    hoverRadius: 8
                                }
                            },
                            animation: {
                                duration: 1500,
                                easing: 'easeInOutQuart'
                            }
                        }
                    });
                    console.log(`Line chart ${canvasId} created successfully`);
                } catch (error) {
                    console.error(`Error creating line chart ${canvasId}:`, error);
                }
            }

            function createDoughnutChart(canvasId, labels, data, label) {
                if (isDestroyed) {
                    console.log(`Chart creation cancelled - component destroyed: ${canvasId}`);
                    return;
                }

                const canvas = document.getElementById(canvasId);
                if (!canvas) {
                    console.log(`Canvas not found: ${canvasId}`);
                    return;
                }

                if (!isCanvasValid(canvas)) {
                    console.warn(`Canvas ${canvasId} is not valid or not ready, delaying chart creation`);
                    setTimeout(() => {
                        if (!isDestroyed) {
                            createDoughnutChart(canvasId, labels, data, label);
                        }
                    }, 100);
                    return;
                }

                // Get the 2D rendering context
                const ctx = canvas.getContext('2d');
                if (!ctx) {
                    console.error(`Could not get 2D context for canvas: ${canvasId}`);
                    return;
                }

                if (!labels || !data || labels.length === 0 || data.length === 0) {
                    console.log(`No data for doughnut chart: ${canvasId}`, { labels, data });
                    return;
                }

                if (charts[canvasId]) {
                    try {
                        charts[canvasId].destroy();
                    } catch (e) {
                        console.warn(`Error destroying chart ${canvasId}:`, e);
                    }
                }

                console.log(`Creating doughnut chart ${canvasId} with ${labels.length} segments`);

                const colors = [
                    '#f59e0b', // yellow-500
                    '#f97316', // orange-500
                    '#ef4444', // red-500
                ];

                try {
                    charts[canvasId] = new Chart(canvas, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: data,
                                backgroundColor: colors,
                                borderColor: '#ffffff',
                                borderWidth: 4,
                                hoverBorderWidth: 6,
                                hoverBorderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            resizeDelay: 200,
                            layout: {
                                padding: {
                                    bottom: 10,
                                    left: 10,
                                    right: 10,
                                    top: 10
                                }
                            },
                            cutout: '65%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 25,
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        font: {
                                            size: 12,
                                            weight: '500'
                                        },
                                        color: '#475569'
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                                    titleColor: '#ffffff',
                                    bodyColor: '#ffffff',
                                    cornerRadius: 12,
                                    padding: 12,
                                    displayColors: true,
                                    titleFont: {
                                        size: 13,
                                        weight: '600'
                                    },
                                    bodyFont: {
                                        size: 14,
                                        weight: '500'
                                    },
                                    callbacks: {
                                        label: function (context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                                            return `${context.label}: ${context.parsed.toLocaleString()} (${percentage}%)`;
                                        }
                                    }
                                }
                            },
                            animation: {
                                duration: 1500,
                                easing: 'easeInOutQuart'
                            }
                        }
                    });
                    console.log(`Doughnut chart ${canvasId} created successfully`);
                } catch (error) {
                    console.error(`Error creating doughnut chart ${canvasId}:`, error);
                }
            }

            function initializeCharts() {
                if (isDestroyed) return;

                // Destroy existing charts first
                destroyExistingCharts();

                // Chart data from Livewire component
                const newUsersLabels = @json($newUsersChartLabels);
                const newUsersValues = @json($newUsersChartValues);
                const activeUsersLabels = @json($activeUsersChartLabels);
                const activeUsersValues = @json($activeUsersChartValues);
                const inactiveUsersLabels = @json($inactiveUsersChartLabels);
                const inactiveUsersValues = @json($inactiveUsersChartValues);

                console.log('Chart data loaded:');
                console.log('New users:', newUsersLabels.length, 'points');
                console.log('Active users:', activeUsersLabels.length, 'points');
                console.log('Inactive users:', inactiveUsersLabels.length, 'points');

                if (newUsersLabels.length > 0) {
                    console.log('New users labels sample:', newUsersLabels.slice(0, 3));
                }

                // Create charts with modern styling
                setTimeout(() => {
                    if (!isDestroyed) {
                        createLineChart('newUsersChart', newUsersLabels, newUsersValues, 'New Users', '#10b981');
                        createLineChart('activeUsersChart', activeUsersLabels, activeUsersValues, 'Active Users', '#3b82f6');
                        createDoughnutChart('inactiveUsersChart', inactiveUsersLabels, inactiveUsersValues, 'Inactive Users');
                    }
                }, 100);
            }

            // Initialize charts
            setTimeout(initializeCharts, 200);

            // Livewire cleanup handlers
            document.addEventListener('livewire:navigating', cleanupCharts);

            // Listen for Livewire events
            document.addEventListener('livewire:initialized', function () {
                console.log('Livewire initialized, setting up event listeners...');

                Livewire.on('dashboardUpdated', function () {
                    if (!isDestroyed) {
                        console.log('Dashboard updated event received, refreshing charts...');
                        setTimeout(initializeCharts, 100);
                    }
                });

                Livewire.on('scaleUpdated', function (autoScale) {
                    if (!isDestroyed) {
                        console.log('Scale updated event received:', autoScale);
                        setTimeout(initializeCharts, 100);
                    }
                });
            });

            // Fallback event listeners for older Livewire versions
            window.addEventListener('dashboardUpdated', function () {
                if (!isDestroyed) {
                    console.log('Dashboard updated via window event, refreshing charts...');
                    setTimeout(initializeCharts, 100);
                }
            });

            window.addEventListener('scaleUpdated', function (event) {
                if (!isDestroyed) {
                    console.log('Scale updated via window event:', event.detail);
                    setTimeout(initializeCharts, 100);
                }
            });

            // Cleanup when page is unloaded
            window.addEventListener('beforeunload', cleanupCharts);
        });
    </script>
@endif

<script>
    // Dark mode toggle functionality
    document.addEventListener('DOMContentLoaded', function () {
        const themeToggle = document.getElementById('themeToggle');
        const sunIcon = document.getElementById('sunIcon');
        const moonIcon = document.getElementById('moonIcon');

        // Check if elements exist
        if (!themeToggle || !sunIcon || !moonIcon) {
            console.error('Dark mode toggle elements not found:', { themeToggle, sunIcon, moonIcon });
            return;
        }

        // Check for saved theme preference or default to light mode
        const savedTheme = localStorage.getItem('theme') || 'light';
        const systemDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

        // Set initial theme
        if (savedTheme === 'dark' || (!savedTheme && systemDarkMode)) {
            document.documentElement.classList.add('dark');
            updateIcons(true);
        } else {
            document.documentElement.classList.remove('dark');
            updateIcons(false);
        }

        // Toggle theme on button click
        themeToggle.addEventListener('click', function () {
            const isDark = document.documentElement.classList.contains('dark');

            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                updateIcons(false);
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                updateIcons(true);
            }
        });

        // Update icon visibility
        function updateIcons(isDark) {
            if (!sunIcon || !moonIcon) return;

            if (isDark) {
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            } else {
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            }
        }

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
            if (!localStorage.getItem('theme')) {
                if (e.matches) {
                    document.documentElement.classList.add('dark');
                    updateIcons(true);
                } else {
                    document.documentElement.classList.remove('dark');
                    updateIcons(false);
                }
            }
        });
    });
</script>
</div>

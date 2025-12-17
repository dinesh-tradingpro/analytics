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
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white ml-3">Community Insights</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-6">
                Discover how your trading community grows and evolves with comprehensive member behavior analysis and
                growth trends.
            </p>
            <a href="{{ route('user-analytics') }}"
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
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white ml-3">Trading Performance</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-6">
                Dive deep into market activity patterns, revenue flows, and financial performance metrics that drive
                your platform's success.
            </p>
            <a href="{{ route('transactions') }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors duration-200">
                View Transactions
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <!-- Tickets Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white ml-3">Support Tickets</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-6">
                Track customer support requests, response times, and resolution metrics to improve service quality.
            </p>
            <a href="{{ route('tickets') }}"
                class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium transition-colors duration-200">
                View Tickets
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <!-- Client Engagement Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white ml-3">Client Engagement</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-6">
                Monitor client interaction patterns, engagement rates, and communication effectiveness across channels.
            </p>
            <button
                class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg font-medium cursor-not-allowed">
                Coming Soon
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </button>
        </div>

        <!-- Partnership IB Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white ml-3">IB Partnership</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-6">
                Manage Interactive Broker partnerships, track referral performance, and optimize commission structures
                for maximum growth.
            </p>
            <button
                class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg font-medium cursor-not-allowed">
                Coming Soon
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </button>
        </div>

        <!-- Revenue Analytics Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <div
                    class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white ml-3">Revenue Analytics</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-6">
                Analyze revenue streams, profit margins, and financial KPIs to maximize your platform's earning
                potential and growth opportunities.
            </p>
            <button
                class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg font-medium cursor-not-allowed">
                Coming Soon
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
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
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Transaction Analytics Dashboard</h2>
        <p class="text-lg text-gray-600 dark:text-gray-300">Monitor transaction flows and performance metrics in
            real-time.</p>
    </div>

    <!-- Error Message -->
    @if (!empty($error))
        <div class="mb-8 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Error loading transaction data</h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                        {{ $error }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Success Message -->
    @if (session('message'))
        <div class="mb-8 bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-700 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif



    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Deposits -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Deposit Volume</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        ${{ number_format(optional($this->depositsApproved)->total_amount ?? 0, 2) }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ number_format(optional($this->depositsApproved)->total_count ?? 0) }} transactions
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Withdrawals -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Withdrawal Volume</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        ${{ number_format(optional($this->withdrawalsApproved)->total_amount ?? 0, 2) }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ number_format(optional($this->withdrawalsApproved)->total_count ?? 0) }} transactions
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Success Rate -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Success Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $this->successRates['overall_success_rate'] }}%
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Deposits: {{ $this->successRates['deposit_success_rate'] }}% |
                        Withdrawals: {{ $this->successRates['withdrawal_success_rate'] }}%
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Net Flow -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Net Flow</p>
                    <p
                        class="text-2xl font-bold {{ $this->volumeMetrics['net_flow'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        ${{ number_format($this->volumeMetrics['net_flow'], 2) }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $this->volumeMetrics['net_flow'] >= 0 ? 'Positive' : 'Negative' }} flow
                    </p>
                </div>
                <div
                    class="w-12 h-12 {{ $this->volumeMetrics['net_flow'] >= 0 ? 'bg-green-100 dark:bg-green-900/50' : 'bg-red-100 dark:bg-red-900/50' }} rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 {{ $this->volumeMetrics['net_flow'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if ($this->volumeMetrics['net_flow'] >= 0)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                        @endif
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics Section -->
    <div class="space-y-8">
        <!-- Current Month Volume Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">This Month's Transaction Volume</h3>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ now()->format('F Y') }}</div>
            </div>
            <div class="h-96">
                <canvas id="monthlyVolumeChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- Past 7 Days Volume Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Past 7 Days Transaction Volume</h3>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ now()->subDays(6)->format('M d') }} - {{ now()->format('M d') }}</div>
            </div>
            <div class="h-96">
                <canvas id="weeklyVolumeChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- Transaction Volume Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transaction Volume Trends</h3>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ ucfirst(str_replace('_', ' ', $selectedPeriod)) }} View</div>
            </div>
            <div class="h-96">
                <canvas id="volumeChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- Two-column layout for additional charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Transaction Distribution Pie Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Transaction Distribution</h3>
                <div class="h-80">
                    <canvas id="distributionChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Success Rate Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Success Rates</h3>
                <div class="h-80">
                    <canvas id="successChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Transactions Table -->
        @if (count($this->topTransactionsList) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Top Transactions by Amount</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    User ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Type</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($this->topTransactionsList as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $transaction['user_id'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    {{ $transaction['type'] === 'deposit' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                            {{ ucfirst($transaction['type']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    {{ $transaction['status'] === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                            {{ ucfirst($transaction['status']) }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        ${{ number_format($transaction['amount'] ?? 0, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ isset($transaction['created_at']) ? \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y H:i') : 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Chart.js Integration -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let volumeChart, distributionChart, successChart, monthlyVolumeChart, weeklyVolumeChart;

            function initCharts() {
                initMonthlyVolumeChart();
                initWeeklyVolumeChart();
                initVolumeChart();
                initDistributionChart();
                initSuccessChart();
            }

            function initMonthlyVolumeChart() {
                const ctx = document.getElementById('monthlyVolumeChart');
                if (!ctx) return;

                const monthlyData = @json($this->monthlyVolumeData);

                if (monthlyVolumeChart) {
                    monthlyVolumeChart.destroy();
                }

                monthlyVolumeChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: monthlyData.labels,
                        datasets: [
                            {
                                label: 'Approved Deposits',
                                data: monthlyData.depositApproved,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#10b981',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointStyle: 'circle'
                            },
                            {
                                label: 'Declined Deposits',
                                data: monthlyData.depositDeclined,
                                borderColor: '#f59e0b',
                                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#f59e0b',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointStyle: 'triangle'
                            },
                            {
                                label: 'Approved Withdrawals',
                                data: monthlyData.withdrawalApproved,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#3b82f6',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointStyle: 'rect'
                            },
                            {
                                label: 'Declined Withdrawals',
                                data: monthlyData.withdrawalDeclined,
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#ef4444',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointStyle: 'rectRot'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151',
                                    padding: 20,
                                    usePointStyle: true
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#6b7280',
                                    callback: function(value) {
                                        return '$' + value.toLocaleString();
                                    }
                                },
                                grid: {
                                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                                }
                            },
                            x: {
                                ticks: {
                                    color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#6b7280'
                                },
                                grid: {
                                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                                }
                            }
                        }
                    }
                });
            }

            function initWeeklyVolumeChart() {
                const ctx = document.getElementById('weeklyVolumeChart');
                if (!ctx) return;

                const weeklyData = @json($this->weeklyVolumeData);

                if (weeklyVolumeChart) {
                    weeklyVolumeChart.destroy();
                }

                weeklyVolumeChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: weeklyData.labels,
                        datasets: [
                            {
                                label: 'Approved Deposits',
                                data: weeklyData.depositApproved,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#10b981',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointStyle: 'circle'
                            },
                            {
                                label: 'Declined Deposits',
                                data: weeklyData.depositDeclined,
                                borderColor: '#f59e0b',
                                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#f59e0b',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointStyle: 'triangle'
                            },
                            {
                                label: 'Approved Withdrawals',
                                data: weeklyData.withdrawalApproved,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#3b82f6',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointStyle: 'rect'
                            },
                            {
                                label: 'Declined Withdrawals',
                                data: weeklyData.withdrawalDeclined,
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#ef4444',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointStyle: 'rectRot'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151',
                                    padding: 20,
                                    usePointStyle: true
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#6b7280',
                                    callback: function(value) {
                                        return '$' + value.toLocaleString();
                                    }
                                },
                                grid: {
                                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                                }
                            },
                            x: {
                                ticks: {
                                    color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#6b7280'
                                },
                                grid: {
                                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                                }
                            }
                        }
                    }
                });
            }

            function initVolumeChart() {
                const ctx = document.getElementById('volumeChart');
                if (!ctx) return;

                const chartData = @json($this->chartData);

                // Extract and combine data from all chart sources
                let combinedLabels = [];
                let depositApprovedData = [];
                let depositDeclinedData = [];
                let withdrawalApprovedData = [];
                let withdrawalDeclinedData = [];

                Object.keys(chartData).forEach(key => {
                    const data = chartData[key];
                    if (data && data.chart_data) {
                        // Data is already parsed from JSON
                        const parsed = data.chart_data;
                        if (parsed && parsed.labels && parsed.labels.length > 0) {
                            // Use the first dataset's labels as our base
                            if (combinedLabels.length === 0) {
                                combinedLabels = parsed.labels;
                            }

                            // Map data based on transaction type and status
                            if (key === 'deposit_approved' && parsed.datasets[0]) {
                                depositApprovedData = parsed.datasets[0].data || [];
                            } else if (key === 'deposit_declined' && parsed.datasets[0]) {
                                depositDeclinedData = parsed.datasets[0].data || [];
                            } else if (key === 'withdrawal_approved' && parsed.datasets[0]) {
                                withdrawalApprovedData = parsed.datasets[0].data || [];
                            } else if (key === 'withdrawal_declined' && parsed.datasets[0]) {
                                withdrawalDeclinedData = parsed.datasets[0].data || [];
                            }
                        }
                    }
                });

                if (volumeChart) {
                    volumeChart.destroy();
                }

                volumeChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: combinedLabels,
                        datasets: [
                            {
                                label: 'Approved Deposits',
                                data: depositApprovedData,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Declined Deposits',
                                data: depositDeclinedData,
                                borderColor: '#f59e0b',
                                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Approved Withdrawals',
                                data: withdrawalApprovedData,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Declined Withdrawals',
                                data: withdrawalDeclinedData,
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#6b7280'
                                },
                                grid: {
                                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                                }
                            },
                            x: {
                                ticks: {
                                    color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#6b7280'
                                },
                                grid: {
                                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                                }
                            }
                        }
                    }
                });
            }

            function initDistributionChart() {
                const ctx = document.getElementById('distributionChart');
                if (!ctx) return;

                const distribution = @json($this->transactionDistribution);

                if (distributionChart) {
                    distributionChart.destroy();
                }

                distributionChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: distribution.labels,
                        datasets: [{
                            data: distribution.data,
                            backgroundColor: distribution.colors,
                            borderWidth: 2,
                            borderColor: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151',
                                    padding: 20
                                }
                            }
                        }
                    }
                });
            }

            function initSuccessChart() {
                const ctx = document.getElementById('successChart');
                if (!ctx) return;

                const successRates = @json($this->successRates);

                if (successChart) {
                    successChart.destroy();
                }

                successChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Deposits', 'Withdrawals', 'Overall'],
                        datasets: [{
                            label: 'Success Rate (%)',
                            data: [
                                successRates.deposit_success_rate,
                                successRates.withdrawal_success_rate,
                                successRates.overall_success_rate
                            ],
                            backgroundColor: [
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(168, 85, 247, 0.8)'
                            ],
                            borderColor: [
                                '#10b981',
                                '#3b82f6',
                                '#a855f7'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                ticks: {
                                    color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#6b7280',
                                    callback: function (value) {
                                        return value + '%';
                                    }
                                },
                                grid: {
                                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                                }
                            },
                            x: {
                                ticks: {
                                    color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#6b7280'
                                },
                                grid: {
                                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                                }
                            }
                        }
                    }
                });
            }

            // Initialize charts
            initCharts();

            // Listen for Livewire updates

            // Handle dark mode changes
            const observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        setTimeout(() => {
                            initCharts();
                        }, 100);
                    }
                });
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>
</div>
</div>
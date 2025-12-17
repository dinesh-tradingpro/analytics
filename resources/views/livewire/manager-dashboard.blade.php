<div class="w-full min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-4xl sm:text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600">
                Manager Analytics
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-3 text-lg">Track manager engagement and response activity</p>
        </div>

        <!-- Top 3 Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-semibold uppercase tracking-wide">Total (7 Days)</p>
                        <p class="text-4xl font-bold mt-2">
                            {{ collect($this->responsesLast7Days ?? [])->sum('responses') }}
                        </p>
                    </div>
                    <div class="p-4 bg-white/20 rounded-xl backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H4a1 1 0 00-1 1v10a1 1 0 001 1h12a1 1 0 001-1V5a1 1 0 00-1-1h-1a1 1 0 000-2 2 2 0 00-2 2v.001h-6V5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-xl p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-100 text-sm font-semibold uppercase tracking-wide">Total (30 Days)</p>
                        <p class="text-4xl font-bold mt-2">
                            {{ collect($this->responsesLast30Days ?? [])->sum('responses') }}
                        </p>
                    </div>
                    <div class="p-4 bg-white/20 rounded-xl backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H4a1 1 0 00-1 1v10a1 1 0 001 1h12a1 1 0 001-1V5a1 1 0 00-1-1h-1a1 1 0 000-2 2 2 0 00-2 2v.001h-6V5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-xl p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-pink-100 text-sm font-semibold uppercase tracking-wide">Total (Annual)</p>
                        <p class="text-4xl font-bold mt-2">
                            {{ collect($this->responsesLastYear ?? [])->sum('responses') }}
                        </p>
                    </div>
                    <div class="p-4 bg-white/20 rounded-xl backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H4a1 1 0 00-1 1v10a1 1 0 001 1h12a1 1 0 001-1V5a1 1 0 00-1-1h-1a1 1 0 000-2 2 2 0 00-2 2v.001h-6V5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid: 2 Columns (Comparison + Distribution) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Comparison Chart: Spans 2 columns -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="bg-gradient-to-r from-indigo-500 to-blue-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">Top Managers Comparison</h3>
                    <p class="text-indigo-100 text-sm mt-1">7 Days vs 30 Days</p>
                </div>
                <div class="p-6" style="height: 400px;">
                    @php
                        $top7 = array_slice($this->responsesLast7Days ?? [], 0, 6, true);
                        $top30 = array_slice($this->responsesLast30Days ?? [], 0, 6, true);
                        $all_managers = collect($top7)->merge($top30)->unique(function($item) {
                            return $item['manager_id'];
                        })->values()->toArray();
                    @endphp
                    @if (!empty($all_managers))
                        <canvas id="comparisonChart" style="display: block;"></canvas>
                    @else
                        <div class="h-full flex items-center justify-center">
                            <p class="text-gray-400">No data available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Distribution Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="bg-gradient-to-r from-orange-500 to-red-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">Response Distribution</h3>
                    <p class="text-orange-100 text-sm mt-1">Last 30 Days</p>
                </div>
                <div class="p-6" style="height: 400px; display: flex; align-items: center; justify-content: center;">
                    @if ($this->responsesLast30Days && count($this->responsesLast30Days) > 0)
                        <canvas id="distributionChart" style="max-width: 90%; max-height: 90%;"></canvas>
                    @else
                        <p class="text-gray-400">No data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top 10 Bar Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-6 py-4">
                <h3 class="text-lg font-bold text-white">Top 10 Managers (All Time)</h3>
                <p class="text-teal-100 text-sm mt-1">Annual performance leaderboard</p>
            </div>
            <div class="p-6" style="height: 450px;">
                @if ($this->responsesLastYear && count($this->responsesLastYear) > 0)
                    <canvas id="topManagersChart" style="display: block;"></canvas>
                @else
                    <div class="h-full flex items-center justify-center">
                        <p class="text-gray-400">No data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Managers by Period -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Last 7 Days -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">Top Managers (7 Days)</h3>
                    <p class="text-blue-100 text-sm mt-1">Recent activity</p>
                </div>
                <div class="p-6">
                    @if ($this->responsesLast7Days && count($this->responsesLast7Days) > 0)
                        <div class="space-y-3">
                            @foreach (array_slice($this->responsesLast7Days, 0, 8) as $item)
                                <div class="flex items-center justify-between group hover:bg-blue-50 dark:hover:bg-gray-700/50 px-3 py-2 rounded-lg transition-colors">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex-1 truncate">{{ $item['name'] }}</span>
                                    <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 whitespace-nowrap">
                                        {{ $item['responses'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-400 text-sm py-8">No responses</p>
                    @endif
                </div>
            </div>

            <!-- Last 30 Days -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="bg-gradient-to-r from-emerald-500 to-green-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">Top Managers (30 Days)</h3>
                    <p class="text-emerald-100 text-sm mt-1">Monthly performance</p>
                </div>
                <div class="p-6">
                    @if ($this->responsesLast30Days && count($this->responsesLast30Days) > 0)
                        <div class="space-y-3">
                            @foreach (array_slice($this->responsesLast30Days, 0, 8) as $item)
                                <div class="flex items-center justify-between group hover:bg-emerald-50 dark:hover:bg-gray-700/50 px-3 py-2 rounded-lg transition-colors">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex-1 truncate">{{ $item['name'] }}</span>
                                    <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300 whitespace-nowrap">
                                        {{ $item['responses'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-400 text-sm py-8">No responses</p>
                    @endif
                </div>
            </div>

            <!-- Last Year -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">Top Managers (Annual)</h3>
                    <p class="text-pink-100 text-sm mt-1">Annual engagement</p>
                </div>
                <div class="p-6">
                    @if ($this->responsesLastYear && count($this->responsesLastYear) > 0)
                        <div class="space-y-3">
                            @foreach (array_slice($this->responsesLastYear, 0, 8) as $item)
                                <div class="flex items-center justify-between group hover:bg-pink-50 dark:hover:bg-gray-700/50 px-3 py-2 rounded-lg transition-colors">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex-1 truncate">{{ $item['name'] }}</span>
                                    <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300 whitespace-nowrap">
                                        {{ $item['responses'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-400 text-sm py-8">No responses</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    let chartsInstance = {};

    function initCharts() {
        Object.values(chartsInstance).forEach(chart => {
            if (chart && typeof chart.destroy === 'function') {
                chart.destroy();
            }
        });
        chartsInstance = {};

        // Comparison Chart
        const comparisonCtx = document.getElementById('comparisonChart');
        if (comparisonCtx) {
            const top7 = @json(array_slice($this->responsesLast7Days ?? [], 0, 6));
            const top30 = @json(array_slice($this->responsesLast30Days ?? [], 0, 6));
            
            const allIds = [...new Set([...top7, ...top30].map(m => m.manager_id))];
            const labels = allIds.map(id => {
                const manager = top7.find(m => m.manager_id === id) || top30.find(m => m.manager_id === id);
                return manager ? manager.name : 'Unknown';
            });
            
            const data7 = allIds.map(id => {
                const manager = top7.find(m => m.manager_id === id);
                return manager ? manager.responses : 0;
            });
            
            const data30 = allIds.map(id => {
                const manager = top30.find(m => m.manager_id === id);
                return manager ? manager.responses : 0;
            });

            chartsInstance.comparison = new Chart(comparisonCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: '7 Days',
                            data: data7,
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 2,
                            borderRadius: 6,
                        },
                        {
                            label: '30 Days',
                            data: data30,
                            backgroundColor: 'rgba(147, 51, 234, 0.8)',
                            borderColor: 'rgba(147, 51, 234, 1)',
                            borderWidth: 2,
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: '#6B7280',
                                font: { size: 12, weight: 'bold' },
                                padding: 15,
                                usePointStyle: true,
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: '#9CA3AF', font: { size: 11 } },
                            grid: { color: 'rgba(107, 114, 128, 0.1)' }
                        },
                        x: {
                            ticks: { color: '#9CA3AF', font: { size: 11 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // Distribution Chart
        const distributionCtx = document.getElementById('distributionChart');
        if (distributionCtx) {
            const data30 = @json(array_slice($this->responsesLast30Days ?? [], 0, 8));
            const colors = [
                '#3B82F6', '#EF4444', '#10B981', '#F59E0B',
                '#8B5CF6', '#EC4899', '#14B8A6', '#F97316'
            ];

            chartsInstance.distribution = new Chart(distributionCtx, {
                type: 'doughnut',
                data: {
                    labels: data30.map(m => m.name),
                    datasets: [{
                        data: data30.map(m => m.responses),
                        backgroundColor: colors.slice(0, data30.length),
                        borderColor: '#fff',
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#6B7280',
                                font: { size: 11 },
                                padding: 12,
                                usePointStyle: true,
                            }
                        }
                    }
                }
            });
        }

        // Top Managers Chart
        const topManagersCtx = document.getElementById('topManagersChart');
        if (topManagersCtx) {
            const yearData = @json(array_slice($this->responsesLastYear ?? [], 0, 10));
            const labels = yearData.map(m => m.name);
            const data = yearData.map(m => m.responses);
            const colors = [
                'rgba(14, 165, 233, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(79, 70, 229, 0.8)',
                'rgba(147, 51, 234, 0.8)',
                'rgba(168, 85, 247, 0.8)',
                'rgba(236, 72, 153, 0.8)',
                'rgba(244, 63, 94, 0.8)',
                'rgba(249, 115, 22, 0.8)',
                'rgba(251, 191, 36, 0.8)',
                'rgba(34, 197, 94, 0.8)'
            ];

            chartsInstance.topManagers = new Chart(topManagersCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Responses',
                        data: data,
                        backgroundColor: colors,
                        borderColor: colors.map(c => c.replace('0.8', '1')),
                        borderWidth: 2,
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { color: '#9CA3AF', font: { size: 11 } },
                            grid: { color: 'rgba(107, 114, 128, 0.1)' }
                        },
                        y: {
                            ticks: { color: '#9CA3AF', font: { size: 11 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCharts);
    } else {
        initCharts();
    }

    document.addEventListener('livewire:navigated', initCharts);
</script>

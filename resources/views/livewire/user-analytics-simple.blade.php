<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">User Analytics Dashboard</h2>
        <p class="text-lg text-gray-600 dark:text-gray-300">Track user engagement and growth patterns with detailed
            analytics.</p>

        <!-- Refresh Button -->
        {{-- <div class="mt-6">
            <button wire:click="refreshData" wire:loading.attr="disabled"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 disabled:opacity-50">
                <svg wire:loading.remove class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <div wire:loading
                    class="w-5 h-5 mr-2 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                <span wire:loading.remove>Refresh Data</span>
                <span wire:loading>Syncing...</span>
            </button>
        </div> --}}
    </div>

    <!-- Error Message -->
    @if ($error)
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
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Error loading analytics data</h3>
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

    <!-- Analytics Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Active Users Analytics -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Active Users</h3>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Last 30 days</span>
                </div>
            </div>

            <!-- Active Users Stats -->
            <div class="mb-6">
                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($activeUsers->total_count ?? 0) }}
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total active users</p>
            </div>

            <!-- Active Users Chart -->
            <div class="h-64">
                <canvas id="activeUsersChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Inactive Users Analytics -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Inactive Users</h3>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Last 30 days</span>
                </div>
            </div>

            <!-- Inactive Users Stats -->
            <div class="mb-6">
                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($inactiveUsers->total_count ?? 0) }}
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Inactive users</p>
            </div>

            <!-- Inactive Users Chart -->
            <div class="h-64">
                <canvas id="inactiveUsersChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- New Users Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">New User Registrations</h3>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Registration trends</span>
            </div>
        </div>

        <!-- New Users Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($newUsersToday) }}
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">New users today</p>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                    {{ number_format($newUsersLast7DaysAvg, 1) }}
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Last 7 days average</p>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                    {{ number_format($newUsersThisMonthAvg, 1) }}
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">This month average</p>
            </div>
        </div>

        <!-- New Users Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
            <!-- Weekly Chart -->
            <div class="space-y-4">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white">Last 7 Days</h4>
                <div class="h-64">
                    <canvas id="newUsersWeeklyChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Monthly Chart -->
            <div class="space-y-4">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white">This Month</h4>
                <div class="h-64">
                    <canvas id="newUsersMonthlyChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Each Month Chart -->
            <div class="space-y-4">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white">Last 12 Months</h4>
                <div class="h-64">
                    <canvas id="newUsersEachMonthChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Inactive Users Status Breakdown -->
    @if (count($inactiveUsersStatusBreakdownData['labels'] ?? []) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">User Distribution by Status</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Breakdown of inactive users by trading status</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Chart -->
                <div class="flex items-center justify-center">
                    <div class="w-full max-w-sm">
                        <canvas id="inactiveUsersStatusChart" width="300" height="300"></canvas>
                    </div>
                </div>

                <!-- Legend Cards -->
                <div class="space-y-3">
                    @php
                        $labels = $inactiveUsersStatusBreakdownData['labels'] ?? [];
                        $data = $inactiveUsersStatusBreakdownData['data'] ?? [];
                        $total = array_sum($data);
                        $colors = [
                            'rgb(239, 68, 68)',
                            'rgb(245, 158, 11)',
                            'rgb(34, 197, 94)',
                            'rgb(59, 130, 246)',
                            'rgb(168, 85, 247)',
                            'rgb(236, 72, 153)'
                        ];
                    @endphp
                    @foreach($labels as $index => $label)
                                    @php
                                        $value = $data[$index] ?? 0;
                                        $percentage = $total > 0 ? round(($value / $total) * 100, 1) : 0;
                                    @endphp
                         <div
                                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow duration-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-4 h-4 rounded-full"
                                                    style="background-color: {{ $colors[$index] ?? 'rgb(156, 163, 175)' }}"></div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($label) }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($value) }} users
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $percentage }}%</p>
                                            </div>
                                        </div>
                                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let activeUsersChart, inactiveUsersChart, inactiveUsersStatusChart;
    let newUsersWeeklyChart, newUsersMonthlyChart, newUsersEachMonthChart;

    // Initialize all charts when the page loads
    document.addEventListener('DOMContentLoaded', function () {
        initializeCharts();
    });

    function initializeCharts() {
        // Active Users Chart
        const activeCtx = document.getElementById('activeUsersChart');
        if (activeCtx) {
            activeUsersChart = new Chart(activeCtx, {
                type: 'line',
                data: {
                    labels: @js($activeUsersChartData['labels'] ?? []),
                    datasets: [{
                        label: 'Active Users',
                        data: @js($activeUsersChartData['data'] ?? []),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: 'rgb(59, 130, 246)',
                        pointHoverBorderColor: '#fff'
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
                            callbacks: {
                                 label: function (context) {
                                    return 'Active Users: ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                 callback: function (value) {
                                    return value.toLocaleString();
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                autoSkip: true,
                                maxTicksLimit: 15
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    }
                }
            });
        }

        // Inactive Users Chart
        const inactiveCtx = document.getElementById('inactiveUsersChart');
        if (inactiveCtx) {
            inactiveUsersChart = new Chart(inactiveCtx, {
                type: 'line',
                data: {
                    labels: @js($inactiveUsersChartData['labels'] ?? []),
                    datasets: [{
                        label: 'Inactive Users',
                        data: @js($inactiveUsersChartData['data'] ?? []),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(239, 68, 68)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: 'rgb(239, 68, 68)',
                        pointHoverBorderColor: '#fff'
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
                            callbacks: { 
                                label: function (context) {
                                    return 'Inactive Users: ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                 callback: function (value) {
                                    return value.toLocaleString();
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                autoSkip: true,
                                maxTicksLimit: 15
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    }
                }
            });
        }

        // New Users Weekly Chart
        const weeklyCtx = document.getElementById('newUsersWeeklyChart');
        if (weeklyCtx) {
            const weeklyGradient = weeklyCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
            weeklyGradient.addColorStop(0, 'rgba(34, 197, 94, 0.8)');
            weeklyGradient.addColorStop(1, 'rgba(34, 197, 94, 0.2)');

            newUsersWeeklyChart = new Chart(weeklyCtx, {
                type: 'bar',
                data: {
                    labels: @js($newUsersWeeklyChartData['labels'] ?? []),
                    datasets: [{
                        label: 'New Users',
                        data: @js($newUsersWeeklyChartData['data'] ?? []),
                        backgroundColor: weeklyGradient,
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return 'New Users: ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                padding: 10
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                padding: 10
                            }
                        }
                    }
                }
            });
        }

        // New Users Monthly Chart
        const monthlyCtx = document.getElementById('newUsersMonthlyChart');
        if (monthlyCtx) {
            const monthlyGradient = monthlyCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
            monthlyGradient.addColorStop(0, 'rgba(168, 85, 247, 0.8)');
            monthlyGradient.addColorStop(1, 'rgba(168, 85, 247, 0.2)');

            newUsersMonthlyChart = new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: @js($newUsersMonthlyChartData['labels'] ?? []),
                    datasets: [{
                        label: 'New Users',
                        data: @js($newUsersMonthlyChartData['data'] ?? []),
                        backgroundColor: monthlyGradient,
                        borderColor: 'rgb(168, 85, 247)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return 'New Users: ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                padding: 10
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                padding: 10
                            }
                        }
                    }
                }
            });
        }

        // New Users Each Month Chart
        const eachMonthCtx = document.getElementById('newUsersEachMonthChart');
        if (eachMonthCtx) {
            const eachMonthGradient = eachMonthCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
            eachMonthGradient.addColorStop(0, 'rgba(245, 158, 11, 0.4)');
            eachMonthGradient.addColorStop(1, 'rgba(245, 158, 11, 0.0)');

            newUsersEachMonthChart = new Chart(eachMonthCtx, {
                type: 'line',
                data: {
                    labels: @js($newUsersEachMonthChartData['labels'] ?? []),
                    datasets: [{
                        label: 'New Users',
                        data: @js($newUsersEachMonthChartData['data'] ?? []),
                        borderColor: 'rgb(245, 158, 11)',
                        backgroundColor: eachMonthGradient,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: 'rgb(245, 158, 11)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: 'rgb(245, 158, 11)',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return 'New Users: ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                padding: 10,
                                callback: function(value) {
                                    return value.toLocaleString();
                          }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                padding: 10
                            }
                        }
                    }
                }
            });
        }

        // Inactive Users Status Chart
        const statusCtx = document.getElementById('inactiveUsersStatusChart');
        if (statusCtx && @js($inactiveUsersStatusBreakdownData['labels'] ?? []).length > 0) {
            const statusData = @js($inactiveUsersStatusBreakdownData['data'] ?? []);
            const statusTotal = statusData.reduce((a, b) => a + b, 0);

            inactiveUsersStatusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: @js($inactiveUsersStatusBreakdownData['labels'] ?? []),
                    datasets: [{
                        data: statusData,
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(168, 85, 247, 0.8)',
                            'rgba(236, 72, 153, 0.8)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 3,
                        hoverOffset: 15,
                        hoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.9)',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const percentage = ((value / statusTotal) * 100).toFixed(1);
                                    return [
                                        context.label + ': ' + value.toLocaleString() + ' users',
                                        'Percentage: ' + percentage + '%'
                                    ];
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Livewire event listeners for chart updates
    document.addEventListener('livewire:init', function () {
        Livewire.on('chartsUpdated', function () {
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
        });
    });
</script>
</div>
<!-- Ticket Analytics -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-10">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Ticket Analytics</h2>
            <p class="text-gray-600 dark:text-gray-300">Track open and closed tickets by date and category.</p>
            @if ($this->latestCreatedAt())
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Latest ticket created at:
                    {{ $this->latestCreatedAt()->format('Y-m-d H:i') }}
                </p>
            @endif
        </div>
        {{-- <div class="flex items-center gap-3">
            <label class="text-sm text-gray-600 dark:text-gray-300">Top categories</label>
            <select wire:model.live="categoryLimit"
                class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                <option value="5">5</option>
                <option value="9">9</option>
                <option value="12">12</option>
            </select>
        </div> --}}
    </div>

    @if ($error)
        <div class="mb-6 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Unable to load tickets</h3>
                    <div class="mt-1 text-sm text-red-700 dark:text-red-300">{{ $error }}</div>
                </div>
            </div>
        </div>
    @endif

    @php
        $statusCounts = $this->statusCounts;
        $data7 = $this->dailyChartData7;
        $data30 = $this->dailyChartData30;
        $dataAll = $this->dailyChartDataAll;
        $categoryData = $this->categoryChartData;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Open Tickets</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                        {{ number_format($statusCounts['open'] ?? 0) }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Closed Tickets</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                        {{ number_format($statusCounts['closed'] ?? 0) }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Avg Processing Time</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                        {{ $this->averageProcessingTime() ?? 'N/A' }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tickets by Date — Last 7 Days</h3>
                    {{-- <p class="text-sm text-gray-500 dark:text-gray-400">Open vs closed for the past 7 days</p> --}}
                </div>
            </div>
            <div class="h-80">
                <canvas id="ticketsByDate7Chart"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tickets by Date — Last 30 Days</h3>
                    {{-- <p class="text-sm text-gray-500 dark:text-gray-400">Open vs closed for the past 30 days</p>
                    --}}
                </div>
            </div>
            <div class="h-80">
                <canvas id="ticketsByDate30Chart"></canvas>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tickets by Date — All Time</h3>
                    {{-- <p class="text-sm text-gray-500 dark:text-gray-400">Open vs closed since first recorded ticket
                    </p> --}}
                </div>
            </div>
            <div class="h-80">
                <canvas id="ticketsByDateAllChart"></canvas>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tickets by Category</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Top {{ $categoryLimit }} categories by ticket
                        volume</p>
                </div>
            </div>
            <div class="h-80">
                <canvas id="ticketsByCategoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let ticketsByDate7Chart;
    let ticketsByDate30Chart;
    let ticketsByDateAllChart;
    let ticketsByCategoryChart;

    const chartPalette = {
        open: 'rgb(59, 130, 246)',
        closed: 'rgb(34, 197, 94)',
        openFill: 'rgba(59, 130, 246, 0.15)',
        closedFill: 'rgba(34, 197, 94, 0.15)'
    };

    function renderTicketCharts() {
        const labels7 = @js($data7['labels'] ?? []);
        const total7 = @js($data7['total'] ?? []);
        const labels30 = @js($data30['labels'] ?? []);
        const total30 = @js($data30['total'] ?? []);
        const labelsAll = @js($dataAll['labels'] ?? []);
        const totalAll = @js($dataAll['total'] ?? []);
        const categoryLabels = @js($categoryData['labels'] ?? []);
        const categoryOpen = @js($categoryData['open'] ?? []);
        const categoryClosed = @js($categoryData['closed'] ?? []);

        const date7Ctx = document.getElementById('ticketsByDate7Chart');
        if (date7Ctx) {
            if (ticketsByDate7Chart) {
                ticketsByDate7Chart.destroy();
            }

            ticketsByDate7Chart = new Chart(date7Ctx, {
                type: 'line',
                data: {
                    labels: labels7,
                    datasets: [
                        {
                            label: 'Total Tickets',
                            data: total7,
                            borderColor: 'rgb(156, 163, 175)',
                            backgroundColor: 'rgba(156, 163, 175, 0.15)',
                            tension: 0.35,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `${context.dataset.label}: ${context.parsed.y?.toLocaleString() ?? 0}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: (v) => v.toLocaleString() } },
                        x: { ticks: { maxRotation: 45, minRotation: 45, autoSkip: true } }
                    }
                }
            });
        }

        const date30Ctx = document.getElementById('ticketsByDate30Chart');
        if (date30Ctx) {
            if (ticketsByDate30Chart) {
                ticketsByDate30Chart.destroy();
            }

            ticketsByDate30Chart = new Chart(date30Ctx, {
                type: 'line',
                data: {
                    labels: labels30,
                    datasets: [
                        {
                            label: 'Total Tickets',
                            data: total30,
                            borderColor: 'rgb(156, 163, 175)',
                            backgroundColor: 'rgba(156, 163, 175, 0.15)',
                            tension: 0.35,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `${context.dataset.label}: ${context.parsed.y?.toLocaleString() ?? 0}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: (v) => v.toLocaleString() } },
                        x: { ticks: { maxRotation: 45, minRotation: 45, autoSkip: true } }
                    }
                }
            });
        }

        const dateAllCtx = document.getElementById('ticketsByDateAllChart');
        if (dateAllCtx) {
            if (ticketsByDateAllChart) {
                ticketsByDateAllChart.destroy();
            }

            ticketsByDateAllChart = new Chart(dateAllCtx, {
                type: 'line',
                data: {
                    labels: labelsAll,
                    datasets: [
                        {
                            label: 'Total Tickets',
                            data: totalAll,
                            borderColor: 'rgb(156, 163, 175)',
                            backgroundColor: 'rgba(156, 163, 175, 0.15)',
                            tension: 0.35,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `${context.dataset.label}: ${context.parsed.y?.toLocaleString() ?? 0}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: (v) => v.toLocaleString() } },
                        x: { ticks: { maxRotation: 45, minRotation: 45, autoSkip: true } }
                    }
                }
            });
        }

        const categoryCtx = document.getElementById('ticketsByCategoryChart');
        if (categoryCtx) {
            if (ticketsByCategoryChart) {
                ticketsByCategoryChart.destroy();
            }

            ticketsByCategoryChart = new Chart(categoryCtx, {
                type: 'bar',
                data: {
                    labels: categoryLabels,
                    datasets: [
                        {
                            label: 'Open',
                            data: categoryOpen,
                            backgroundColor: chartPalette.openFill,
                            borderColor: chartPalette.open,
                            borderWidth: 1,
                            borderRadius: 6
                        },
                        {
                            label: 'Closed',
                            data: categoryClosed,
                            backgroundColor: chartPalette.closedFill,
                            borderColor: chartPalette.closed,
                            borderWidth: 1,
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `${context.dataset.label}: ${context.parsed.y?.toLocaleString() ?? 0}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: (v) => v.toLocaleString() } },
                        x: { ticks: { autoSkip: false } },
                    }
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', renderTicketCharts);
    document.addEventListener('livewire:update', renderTicketCharts);
    document.addEventListener('livewire:navigated', renderTicketCharts);
</script>

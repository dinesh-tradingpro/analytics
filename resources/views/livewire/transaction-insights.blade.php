<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Transaction Insights</h2>
        <p class="text-lg text-gray-600 dark:text-gray-300">Analyze top transactions and repeat user patterns</p>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Time Period</label>
                <select wire:model.live="dateRange" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="7">Last 7 Days</option>
                    <option value="30">Last 30 Days</option>
                    <option value="90">Last 90 Days</option>
                    <option value="365">Last Year</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Results Limit</label>
                <select wire:model.live="topLimit" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="5">Top 5</option>
                    <option value="10">Top 10</option>
                    <option value="20">Top 20</option>
                    <option value="50">Top 50</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    @if ($error)
        <div class="mb-8 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 rounded-lg p-4">
            <p class="text-sm text-red-800 dark:text-red-200">{{ $error }}</p>
        </div>
    @endif

    <!-- Loading State -->
    @if ($loading)
        <div class="text-center py-12">
            <div class="inline-block w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Loading insights...</p>
        </div>
    @endif

    <!-- Top Transactions Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Top Deposits -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Highest Deposits</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Top deposit transactions by amount</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
            </div>

            <div class="space-y-3">
                @forelse($topDeposits as $index => $transaction)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:shadow-md transition-shadow">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">User: {{ $transaction->from_login_sid }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->transaction_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-green-600 dark:text-green-400">${{ number_format($transaction->processed_amount, 2) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No deposit data available</p>
                @endforelse
            </div>
        </div>

        <!-- Top Withdrawals -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Highest Withdrawals</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Top withdrawal transactions by amount</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </div>
            </div>

            <div class="space-y-3">
                @forelse($topWithdrawals as $index => $transaction)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:shadow-md transition-shadow">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">User: {{ $transaction->from_login_sid }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->transaction_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-red-600 dark:text-red-400">${{ number_format($transaction->processed_amount, 2) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No withdrawal data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Repeat Transaction Users Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Repeat Deposit Users -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Most Frequent Depositors</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Users with the most deposit transactions</p>
            </div>

            <div class="space-y-4">
                @forelse($repeatDepositUsers as $index => $user)
                    <div class="p-4 bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 rounded-lg border border-green-200 dark:border-green-700">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">#{{ $index + 1 }}</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">User: {{ $user->from_login_sid }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->first_transaction->format('M d') }} - {{ $user->last_transaction->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-4 gap-3 text-center">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Transactions</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->transaction_count }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount</p>
                                <p class="text-xl font-bold text-green-600 dark:text-green-400">${{ number_format($user->total_amount, 0) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Avg Amount</p>
                                <p class="text-xl font-bold text-blue-600 dark:text-blue-400">${{ number_format($user->avg_amount, 0) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Avg Time</p>
                                <p class="text-xl font-bold text-purple-600 dark:text-purple-400">{{ $user->avg_processing_time_seconds ? gmdate('i:s', $user->avg_processing_time_seconds) : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No repeat depositor data available</p>
                @endforelse
            </div>
        </div>

        <!-- Repeat Withdrawal Users -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Most Frequent Withdrawers</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Users with the most withdrawal transactions</p>
            </div>

            <div class="space-y-4">
                @forelse($repeatWithdrawalUsers as $index => $user)
                    <div class="p-4 bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 rounded-lg border border-red-200 dark:border-red-700">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl font-bold text-red-600 dark:text-red-400">#{{ $index + 1 }}</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">User: {{ $user->from_login_sid }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->first_transaction->format('M d') }} - {{ $user->last_transaction->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-4 gap-3 text-center">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Transactions</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->transaction_count }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount</p>
                                <p class="text-xl font-bold text-red-600 dark:text-red-400">${{ number_format($user->total_amount, 0) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Avg Amount</p>
                                <p class="text-xl font-bold text-orange-600 dark:text-orange-400">${{ number_format($user->avg_amount, 0) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Avg Time</p>
                                <p class="text-xl font-bold text-purple-600 dark:text-purple-400">{{ $user->avg_processing_time_seconds ? gmdate('i:s', $user->avg_processing_time_seconds) : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No repeat withdrawer data available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

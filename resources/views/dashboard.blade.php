<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Owner - Teh Poci Kasir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Low Stock Warnings -->
            @if ($lowStockItems->isNotEmpty())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Peringatan! Stok Bahan Baku Hampir Habis (Di bawah batas minimum):
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($lowStockItems as $item)
                                        <li><strong>{{ $item->name }}</strong>: Sisa {{ number_format($item->stock, 1) }} {{ $item->unit }} (Batas minimum: {{ number_format($item->min_stock, 1) }} {{ $item->unit }})</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Pendapatan Hari Ini -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-b-4 border-emerald-500">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pendapatan Hari Ini</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">Rp {{ number_format($totalIncomeToday, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Pendapatan Bulan Ini -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-b-4 border-teal-500">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pendapatan Bulan Ini</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">Rp {{ number_format($totalIncomeMonth, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Cup Terjual Hari Ini -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-b-4 border-sky-500">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Cup Terjual Hari Ini</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalCupsSoldToday }} <span class="text-lg font-normal text-gray-500">cup</span></p>
                    </div>
                </div>

                <!-- Cup Terjual Bulan Ini -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-b-4 border-indigo-500">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Cup Terjual Bulan Ini</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalCupsSoldMonth }} <span class="text-lg font-normal text-gray-500">cup</span></p>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h3>
                        <a href="{{ route('checkout.history') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">Lihat Semua Riwayat &rarr;</a>
                    </div>

                    @if ($recentTransactions->isEmpty())
                        <div class="text-center py-6 text-gray-500">
                            Belum ada transaksi hari ini.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Invoice</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Belanja</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($recentTransactions as $tx)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ $tx->invoice_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $tx->user ? $tx->user->name : 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-semibold">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

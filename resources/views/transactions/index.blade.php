<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Data Transaksi Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Statistics Widgets -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Widget 1: Total Pendapatan / Omzet -->
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-md p-6 text-white relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-20 text-white">
                        <svg class="h-28 w-28" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v.847c-.57.067-1.127.23-1.637.476a.75.75 0 1 0 .633 1.36c.35-.163.727-.27 1.124-.316V10.5h-1.12c-1.18 0-2.072.844-2.072 1.838 0 .937.818 1.697 1.87 1.815v.847a.75.75 0 0 0 1.5 0v-.806c.642-.047 1.25-.213 1.796-.484a.75.75 0 1 0-.68-1.336c-.396.198-.838.318-1.316.35v-2.316h1.12c1.18 0 2.072-.844 2.072-1.838 0-.937-.818-1.697-1.87-1.815V6ZM10.5 12.338V11.25h.37c.394 0 .572.2.572.338 0 .148-.178.338-.572.338h-.37Zm3-3v1.088h-.37c-.394 0-.572-.2-.572-.338 0-.148.178-.338.572-.338h.37Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-emerald-100 text-sm font-semibold uppercase tracking-wider">Total Pendapatan</p>
                    <h3 class="text-3xl font-extrabold mt-2 font-mono" id="display-total-omzet">
                        Rp {{ number_format($totalOmzet, 0, ',', '.') }}
                    </h3>
                    <p class="text-xs text-emerald-100 mt-4" id="text-desc-omzet">Akumulasi omzet khusus hari ini</p>
                </div>

                <!-- Widget 2: Total Transaksi Berhasil -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-md p-6 text-white relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-15">
                        <svg class="h-28 w-28" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                    </div>
                    <p class="text-indigo-100 text-sm font-semibold uppercase tracking-wider">Transaksi</p>
                    <h3 class="text-3xl font-extrabold mt-2 font-mono" id="display-total-tx">
                        {{ number_format($totalTransactions, 0, ',', '.') }} Tx
                    </h3>
                    <p class="text-xs text-indigo-100 mt-4" id="text-desc-tx">Jumlah pesanan selesai hari ini</p>
                </div>

                <!-- Widget 3: Total Cup Terjual -->
                <div class="bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl shadow-md p-6 text-white relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-15">
                        <svg class="h-28 w-28" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 21h18v-2H2v2zM20 8h-2V5h2v3zm-4-3v3H4v10h12V5h4c1.1 0 2-.9 2-2V3c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v2h14z"/>
                        </svg>
                    </div>
                    <p class="text-orange-100 text-sm font-semibold uppercase tracking-wider">Cup Terjual</p>
                    <h3 class="text-3xl font-extrabold mt-2 font-mono" id="display-total-cups">
                        {{ number_format($totalCups, 0, ',', '.') }} Cup
                    </h3>
                    <p class="text-xs text-orange-100 mt-4" id="text-desc-cups">Kuantitas seluruh item menu terjual hari ini</p>
                </div>
            </div>

            <!-- Chart Container Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Grafik Tren Pendapatan (7 Hari Terakhir)</h3>
                <div class="w-full h-72 flex justify-center">
                    <canvas id="salesChart" class="w-full"></canvas>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Riwayat Penjualan</h3>

                    @if ($transactions->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            Belum ada riwayat transaksi penjualan.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            No. Nota
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Waktu Transaksi
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Kasir
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Detail Pembelian
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Total Belanja
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Bayar & Kembalian
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-700">
                                    @foreach ($transactions as $tx)
                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                            <!-- Nota -->
                                            <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-gray-900">
                                                {{ $tx->invoice_number }}
                                            </td>
                                            <!-- Waktu -->
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                                {{ $tx->created_at->timezone($tx->timezone ?? 'Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') }} {{ $tx->created_at->timezone($tx->timezone ?? 'Asia/Jakarta')->format('T') }}
                                            </td>
                                            <!-- Kasir -->
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-950">
                                                {{ $tx->user ? $tx->user->name : 'N/A' }}
                                            </td>
                                            <!-- Detail Pembelian -->
                                            <td class="px-6 py-4 max-w-xs md:max-w-md truncate" title="{{ $tx->details->map(fn($d) => ($d->menu ? $d->menu->name : 'Menu Dihapus') . ' (' . ($d->menu ? $d->menu->size : '-') . ') x' . $d->quantity)->implode(', ') }}">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($tx->details as $d)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-50 text-orange-800 border border-orange-100">
                                                            {{ $d->quantity }}x {{ $d->menu ? $d->menu->name : 'Menu Dihapus' }} ({{ $d->menu ? $d->menu->size : '-' }})
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <!-- Total Belanja -->
                                            <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-emerald-600">
                                                Rp {{ number_format($tx->total_amount, 0, ',', '.') }}
                                            </td>
                                            <!-- Bayar & Kembalian -->
                                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-mono space-y-1">
                                                <div>Bayar: <span class="font-bold text-gray-700">Rp {{ number_format($tx->payment_amount, 0, ',', '.') }}</span></div>
                                                <div>Kembali: <span class="font-bold text-emerald-600">Rp {{ number_format($tx->change_amount, 0, ',', '.') }}</span></div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Inisialisasi Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            const dates = {!! json_encode($dates) !!};
            const fullDates = {!! json_encode($fullDates) !!};
            const totals = {!! json_encode($totals) !!};
 
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Pendapatan Harian (Rp)',
                        data: totals,
                        backgroundColor: '#10b981', // Emerald green brand color
                        borderColor: '#059669',
                        borderWidth: 1.5,
                        borderRadius: 8,
                        hoverBackgroundColor: '#047857'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (event, activeElements, chart) => {
                        if (activeElements.length > 0) {
                            const index = activeElements[0].index;
                            const label = dates[index];
                            const isoDate = fullDates[index];
                            fetchMetricsForDate(isoDate, label);
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                },
                                font: {
                                    family: 'ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace',
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    weight: '600'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });

            function fetchMetricsForDate(date, label) {
                fetch(`/api/sales-metrics-by-date?date=${date}`)
                    .then(res => res.json())
                    .then(data => {
                        // Update gross total
                        document.getElementById('display-total-omzet').innerText = 'Rp ' + data.omzet.toLocaleString('id-ID');
                        // Update transaction count
                        document.getElementById('display-total-tx').innerText = data.transaksi.toLocaleString('id-ID') + ' Tx';
                        // Update cup count
                        document.getElementById('display-total-cups').innerText = data.cup.toLocaleString('id-ID') + ' Cup';

                        // Update sub-text dynamically
                        document.getElementById('text-desc-omzet').innerText = 'Data pada tanggal ' + label;
                        document.getElementById('text-desc-tx').innerText = 'Jumlah pesanan selesai pada ' + label;
                        document.getElementById('text-desc-cups').innerText = 'Kuantitas menu terjual pada ' + label;
                    })
                    .catch(err => console.error(err));
            }
        });
    </script>
</x-app-layout>

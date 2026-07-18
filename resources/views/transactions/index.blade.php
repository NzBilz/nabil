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
                    <div class="absolute -right-4 -bottom-4 opacity-15">
                        <svg class="h-28 w-28" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z"/>
                        </svg>
                    </div>
                    <p class="text-emerald-100 text-sm font-semibold uppercase tracking-wider">Total Pendapatan / Omzet</p>
                    <h3 class="text-3xl font-extrabold mt-2 font-mono">
                        Rp {{ number_format($totalOmzet, 0, ',', '.') }}
                    </h3>
                    <p class="text-xs text-emerald-100 mt-4">Akumulasi omzet dari seluruh transaksi sukses</p>
                </div>

                <!-- Widget 2: Total Transaksi Berhasil -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-md p-6 text-white relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-15">
                        <svg class="h-28 w-28" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                    </div>
                    <p class="text-indigo-100 text-sm font-semibold uppercase tracking-wider">Transaksi Berhasil</p>
                    <h3 class="text-3xl font-extrabold mt-2 font-mono">
                        {{ number_format($totalTransactions, 0, ',', '.') }} <span class="text-lg font-normal">Tx</span>
                    </h3>
                    <p class="text-xs text-indigo-100 mt-4">Jumlah pesanan selesai dicatat</p>
                </div>

                <!-- Widget 3: Total Cup Terjual -->
                <div class="bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl shadow-md p-6 text-white relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-15">
                        <svg class="h-28 w-28" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 21h18v-2H2v2zM20 8h-2V5h2v3zm-4-3v3H4v10h12V5h4c1.1 0 2-.9 2-2V3c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v2h14z"/>
                        </svg>
                    </div>
                    <p class="text-orange-100 text-sm font-semibold uppercase tracking-wider">Total Cup Terjual</p>
                    <h3 class="text-3xl font-extrabold mt-2 font-mono">
                        {{ number_format($totalCups, 0, ',', '.') }} <span class="text-lg font-normal">Cup</span>
                    </h3>
                    <p class="text-xs text-orange-100 mt-4">Kuantitas seluruh item menu yang terjual</p>
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
                                                {{ $tx->created_at->locale('id')->translatedFormat('d M Y, H:i') }}
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
</x-app-layout>

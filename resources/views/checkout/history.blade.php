<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Penjualan - Owner') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Daftar Semua Transaksi Penjualan</h3>

                    @if ($transactions->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            Belum ada riwayat transaksi penjualan.
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach ($transactions as $tx)
                                <div class="border border-gray-200 rounded-lg p-4 bg-slate-50 shadow-sm">
                                    <!-- Header Info -->
                                    <div class="flex flex-col md:flex-row justify-between md:items-center pb-3 border-b mb-3 text-sm">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono font-bold text-gray-900 text-base">{{ $tx->invoice_number }}</span>
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded bg-emerald-100 text-emerald-800">Selesai</span>
                                            </div>
                                            <p class="text-gray-500">
                                                Dilayani oleh: <strong>{{ $tx->user ? $tx->user->name : 'N/A' }}</strong> pada {{ $tx->created_at->timezone($tx->timezone ?? 'Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') }} {{ $tx->created_at->timezone($tx->timezone ?? 'Asia/Jakarta')->format('T') }}
                                            </p>
                                        </div>
                                        <div class="text-right mt-2 md:mt-0 font-mono">
                                            <div class="text-xs text-gray-500">Total Transaksi</div>
                                            <div class="text-lg font-bold text-emerald-600">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</div>
                                        </div>
                                    </div>

                                    <!-- Details Items -->
                                    <div class="space-y-2">
                                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Item Terbeli</div>
                                        @foreach ($tx->details as $d)
                                            <div class="flex justify-between items-center text-sm py-1 border-b border-gray-100 last:border-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-gray-900 font-semibold">{{ $d->menu ? $d->menu->name : 'Menu Dihapus' }}</span>
                                                    <span class="px-1.5 py-0.2 text-[10px] bg-gray-200 text-gray-700 rounded">{{ $d->menu ? $d->menu->size : '-' }}</span>
                                                    <span class="text-gray-400">x{{ $d->quantity }}</span>
                                                </div>
                                                <div class="font-mono text-gray-600">
                                                    Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Payment details footer -->
                                    <div class="mt-4 pt-3 border-t border-dashed border-gray-300 flex justify-end gap-6 text-xs text-gray-600 font-mono">
                                        <div>Bayar: <strong class="text-gray-800">Rp {{ number_format($tx->payment_amount, 0, ',', '.') }}</strong></div>
                                        <div>Kembalian: <strong class="text-emerald-700">Rp {{ number_format($tx->change_amount, 0, ',', '.') }}</strong></div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $transactions->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

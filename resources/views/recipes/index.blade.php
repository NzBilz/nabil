<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Resep Menu - Teh Poci') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash success alerts -->
            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-md shadow-sm">
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Manajemen Resep Menu</h3>
                            <p class="text-xs text-gray-500 mt-1">Tentukan takaran bahan baku (Gelas, Sedotan, Bubuk Teh, Gula, dll) yang otomatis berkurang saat menu ini di-checkout di kasir.</p>
                        </div>
                    </div>

                    @if ($menus->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            Belum ada menu yang terdaftar. Silakan tambahkan menu di menu manajemen terlebih dahulu.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Menu</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Ukuran</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Harga Jual</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Resep / Bahan Baku (Per Cup)</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($menus as $menu)
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900">{{ $menu->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                                @if ($menu->size === 'Large')
                                                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                        {{ $menu->size }}
                                                    </span>
                                                @else
                                                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                                                        {{ $menu->size }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900 font-mono">
                                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                @if ($menu->recipes->isEmpty())
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 border border-red-100 italic">
                                                        Belum ada resep (Stok tidak berkurang)
                                                    </span>
                                                @else
                                                    <div class="flex flex-wrap gap-1.5 max-w-lg">
                                                        @foreach ($menu->recipes as $r)
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                                                <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                                </svg>
                                                                <strong>{{ $r->inventory ? $r->inventory->name : 'Bahan Baku Dihapus' }}</strong>: 
                                                                <span class="ml-1 font-mono font-semibold">{{ number_format($r->quantity, 1) }}</span> {{ $r->inventory ? $r->inventory->unit : '' }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <a href="{{ route('recipes.edit', $menu->id) }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 hover:text-indigo-900 border border-indigo-200 py-1.5 px-3 rounded-md text-xs font-semibold shadow-sm transition">
                                                    Atur Resep
                                                </a>
                                            </td>
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

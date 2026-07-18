<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Atur Resep Menu - Teh Poci') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                Atur Resep: {{ $menu->name }} 
                                <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded bg-orange-100 text-orange-800">
                                    {{ $menu->size }}
                                </span>
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">Masukkan jumlah takaran bahan baku yang dibutuhkan untuk memproduksi satu cup menu ini. Kosongkan atau isi 0 jika bahan tidak digunakan.</p>
                        </div>
                        <a href="{{ route('recipes.index') }}" class="text-sm text-slate-500 hover:text-slate-800 transition">&larr; Kembali</a>
                    </div>

                    <form action="{{ route('recipes.update', $menu->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="overflow-hidden border border-slate-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Bahan Baku</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Saat Ini</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Satuan</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-40">Takaran Resep</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($inventories as $index => $inv)
                                        @php
                                            // Find existing recipe quantity for this inventory item
                                            $existingRecipe = $menu->recipes->firstWhere('inventory_id', $inv->id);
                                            $qty = $existingRecipe ? $existingRecipe->quantity : '';
                                        @endphp
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">
                                                {{ $inv->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-mono">
                                                {{ number_format($inv->stock, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">
                                                {{ $inv->unit }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <input type="hidden" name="ingredients[{{ $index }}][inventory_id]" value="{{ $inv->id }}">
                                                <div class="flex items-center gap-2">
                                                    <input type="number" 
                                                           name="ingredients[{{ $index }}][quantity]" 
                                                           value="{{ old('ingredients.'.$index.'.quantity', $qty) }}" 
                                                           step="0.01" 
                                                           min="0" 
                                                           placeholder="0.00"
                                                           class="w-24 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-mono font-semibold py-1">
                                                    <span class="text-xs text-slate-500 font-medium">{{ $inv->unit }}</span>
                                                </div>
                                                @error('ingredients.'.$index.'.quantity')
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end gap-3 border-t pt-6">
                            <a href="{{ route('recipes.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold py-2 px-4 rounded-md transition text-sm">
                                Batal
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md transition text-sm shadow-md">
                                Simpan Resep
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Bahan Baku - Owner') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Formulir Tambah Bahan Baku</h3>
                        <a href="{{ route('inventories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali ke Daftar</a>
                    </div>

                    <form action="{{ route('inventories.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Nama Bahan Baku -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Bahan Baku</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Cup Medium, Sedotan, Gula Cair, Bubuk Teh Original"
                                   class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Stok Awal -->
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700">Stok Awal</label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" required step="0.01" min="0" placeholder="0.00"
                                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono">
                                @error('stock')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Satuan -->
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700">Satuan</label>
                                <input type="text" name="unit" id="unit" value="{{ old('unit') }}" required placeholder="Contoh: pcs, gram, ml"
                                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @error('unit')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Batas Minimum -->
                            <div>
                                <label for="min_stock" class="block text-sm font-medium text-gray-700">Batas Stok Minimum</label>
                                <input type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', 0) }}" required step="0.01" min="0" placeholder="0.00"
                                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono">
                                @error('min_stock')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end gap-3 border-t pt-6">
                            <a href="{{ route('inventories.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-md transition text-sm">
                                Batal
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md transition text-sm shadow-md">
                                Simpan Bahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

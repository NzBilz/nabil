<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ubah Menu - Owner') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Formulir Ubah Menu</h3>
                        <a href="{{ route('menus.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali ke Daftar</a>
                    </div>

                    <form action="{{ route('menus.update', $menu->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Nama Menu -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Menu (Varian)</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $menu->name) }}" required
                                   class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Ukuran -->
                            <div>
                                <label for="size" class="block text-sm font-medium text-gray-700">Ukuran</label>
                                <select name="size" id="size" required
                                        class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm">
                                    <option value="Medium" {{ old('size', $menu->size) == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Large" {{ old('size', $menu->size) == 'Large' ? 'selected' : '' }}>Large</option>
                                </select>
                                @error('size')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Harga -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Harga Jual (Rp)</label>
                                <input type="number" name="price" id="price" value="{{ old('price', $menu->price) }}" required min="0"
                                       class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm">
                                @error('price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Recipe / Ingredients section -->
                        <div class="border-t pt-6">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h4 class="text-md font-semibold text-gray-800">Resep Bahan Baku (Opsional)</h4>
                                    <p class="text-xs text-gray-500">Tentukan bahan baku yang berkurang setiap kali menu ini terjual.</p>
                                </div>
                                <button type="button" onclick="addIngredientRow()"
                                        class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-1.5 px-3 rounded text-xs transition">
                                    + Tambah Bahan Baku
                                </button>
                            </div>

                            <!-- Dynamic rows container -->
                            <div id="recipe-rows-container" class="space-y-3">
                                @php
                                    $ingredients = old('ingredients', $menu->recipes);
                                @endphp

                                @foreach ($ingredients as $index => $ing)
                                    @php
                                        // Handle whether it is an Eloquent Model or array (from old input)
                                        $invId = is_array($ing) ? $ing['inventory_id'] : $ing->inventory_id;
                                        $quantity = is_array($ing) ? $ing['quantity'] : $ing->quantity;
                                    @endphp
                                    <div class="flex items-center gap-3 bg-slate-50 p-3 rounded border border-gray-200" id="recipe-row-{{ $index }}">
                                        <div class="flex-1">
                                            <select name="ingredients[{{ $index }}][inventory_id]" required
                                                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                                <option value="">-- Pilih Bahan Baku --</option>
                                                @foreach ($inventories as $inv)
                                                    <option value="{{ $inv->id }}" {{ $invId == $inv->id ? 'selected' : '' }}>
                                                        {{ $inv->name }} (Sisa: {{ number_format($inv->stock, 1) }} {{ $inv->unit }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-1/3 flex items-center gap-2">
                                            <input type="number" name="ingredients[{{ $index }}][quantity]" required step="0.01" min="0.01" value="{{ $quantity }}" placeholder="Jumlah"
                                                   class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-mono">
                                        </div>
                                        <button type="button" onclick="removeIngredientRow({{ $index }})"
                                                class="text-red-500 hover:text-red-700 focus:outline-none p-1">
                                            Hapus
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end gap-3 border-t pt-6">
                            <a href="{{ route('menus.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-md transition text-sm">
                                Batal
                            </a>
                            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-md transition text-sm shadow-md">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Inventories passed to JS -->
    <script>
        const inventories = @json($inventories);
        let rowIndex = {{ count($ingredients) }};

        function addIngredientRow() {
            const container = document.getElementById('recipe-rows-container');
            const rowId = rowIndex;

            let selectOptions = '<option value="">-- Pilih Bahan Baku --</option>';
            inventories.forEach(inv => {
                selectOptions += `<option value="${inv.id}">${inv.name} (${inv.unit})</option>`;
            });

            const rowHtml = `
                <div class="flex items-center gap-3 bg-slate-50 p-3 rounded border border-gray-200" id="recipe-row-${rowId}">
                    <div class="flex-1">
                        <select name="ingredients[${rowId}][inventory_id]" required
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            ${selectOptions}
                        </select>
                    </div>
                    <div class="w-1/3 flex items-center gap-2">
                        <input type="number" name="ingredients[${rowId}][quantity]" required step="0.01" min="0.01" placeholder="Jumlah"
                               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-mono">
                    </div>
                    <button type="button" onclick="removeIngredientRow(${rowId})"
                            class="text-red-500 hover:text-red-700 focus:outline-none p-1">
                        Hapus
                    </button>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', rowHtml);
            rowIndex++;
        }

        function removeIngredientRow(id) {
            const row = document.getElementById(`recipe-row-${id}`);
            if (row) {
                row.remove();
            }
        }
    </script>
</x-app-layout>

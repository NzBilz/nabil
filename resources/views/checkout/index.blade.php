<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kasir / Checkout - Teh Poci') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Message Alerts -->
            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Menu Grid -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pilih Menu Es Teh Poci</h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($menus as $menu)
                                <div class="border border-gray-200 rounded-lg p-4 flex flex-col justify-between hover:shadow-md transition bg-slate-50 cursor-pointer"
                                     onclick="addToCart({{ $menu->id }}, '{{ $menu->name }}', '{{ $menu->size }}', {{ $menu->price }})">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $menu->name }}</h4>
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                                {{ $menu->size }}
                                            </span>
                                        </div>
                                        <p class="text-emerald-600 font-bold mt-2">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                    </div>
                                    <button class="mt-4 w-full bg-orange-500 hover:bg-orange-600 text-white py-1 px-3 rounded text-sm font-medium transition">
                                        + Tambah
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right: Shopping Cart & Payment -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6 h-fit">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Keranjang Belanja</h3>
                    
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                        @csrf
                        
                        <!-- Cart Container -->
                        <div id="cart-items" class="space-y-4 max-h-96 overflow-y-auto mb-4 pr-1">
                            <p class="text-gray-500 text-center py-6" id="empty-cart-msg">Keranjang kosong. Klik menu di kiri untuk menambahkan.</p>
                        </div>

                        <!-- Bill details -->
                        <div class="border-t pt-4 space-y-2 text-sm">
                            <div class="flex justify-between font-bold text-lg text-gray-900">
                                <span>Total Belanja:</span>
                                <span>Rp <span id="display-total">0</span></span>
                            </div>
                            
                            <div class="mt-4">
                                <label for="payment_amount" class="block font-medium text-gray-700 mb-1">Jumlah Uang Dibayar (Rp)</label>
                                <input type="number" name="payment_amount" id="payment_amount" min="0" required
                                       class="w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm font-mono text-lg"
                                       oninput="calculateChange()">
                                @error('payment_amount')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-between items-center bg-gray-100 p-3 rounded-md mt-2 font-bold text-gray-800">
                                <span>Uang Kembalian:</span>
                                <span class="text-xl text-emerald-600">Rp <span id="display-change">0</span></span>
                            </div>
                        </div>

                        <button type="submit" id="submit-btn" disabled
                                class="mt-6 w-full py-3 px-4 rounded-md text-white font-bold text-lg transition shadow-md bg-gray-400 cursor-not-allowed">
                            Proses Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle Cart operations -->
    <script>
        let cart = [];

        function addToCart(id, name, size, price) {
            // Check if item already exists in cart
            let existingItem = cart.find(item => item.menu_id === id);

            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    menu_id: id,
                    name: name,
                    size: size,
                    price: price,
                    quantity: 1
                });
            }

            renderCart();
        }

        function updateQuantity(id, change) {
            let item = cart.find(item => item.menu_id === id);
            if (!item) return;

            item.quantity += change;
            if (item.quantity <= 0) {
                cart = cart.filter(item => item.menu_id !== id);
            }

            renderCart();
        }

        function removeItem(id) {
            cart = cart.filter(item => item.menu_id !== id);
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cart-items');
            const emptyMsg = document.getElementById('empty-cart-msg');
            const submitBtn = document.getElementById('submit-btn');

            if (cart.length === 0) {
                container.innerHTML = `<p class="text-gray-500 text-center py-6" id="empty-cart-msg">Keranjang kosong. Klik menu di kiri untuk menambahkan.</p>`;
                document.getElementById('display-total').innerText = '0';
                submitBtn.disabled = true;
                submitBtn.className = "mt-6 w-full py-3 px-4 rounded-md text-white font-bold text-lg transition shadow-md bg-gray-400 cursor-not-allowed";
                calculateChange();
                return;
            }

            let cartHtml = '';
            let total = 0;

            cart.forEach((item, index) => {
                const subtotal = item.price * item.quantity;
                total += subtotal;

                cartHtml += `
                    <div class="flex items-center justify-between border-b pb-3" id="cart-row-${item.menu_id}">
                        <div class="flex-1 min-w-0 pr-2">
                            <p class="text-sm font-bold text-gray-900 truncate">${item.name}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-1.5 py-0.5 text-[10px] font-semibold bg-orange-100 text-orange-800 rounded">
                                    ${item.size}
                                </span>
                                <span class="text-xs text-gray-500 font-mono">Rp ${item.price.toLocaleString('id-ID')}</span>
                            </div>
                        </div>

                        <!-- Hidden Inputs for Form Submission -->
                        <input type="hidden" name="items[${index}][menu_id]" value="${item.menu_id}">
                        <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">

                        <div class="flex items-center gap-3">
                            <!-- Qty Controls -->
                            <div class="flex items-center border rounded border-gray-300 bg-white">
                                <button type="button" class="px-2 py-0.5 text-gray-600 hover:bg-gray-100 font-bold" onclick="updateQuantity(${item.menu_id}, -1)">-</button>
                                <span class="px-2 py-0.5 text-sm font-semibold font-mono">${item.quantity}</span>
                                <button type="button" class="px-2 py-0.5 text-gray-600 hover:bg-gray-100 font-bold" onclick="updateQuantity(${item.menu_id}, 1)">+</button>
                            </div>
                            
                            <div class="w-20 text-right font-mono text-sm font-bold text-gray-900">
                                Rp ${subtotal.toLocaleString('id-ID')}
                            </div>

                            <button type="button" class="text-red-500 hover:text-red-700" onclick="removeItem(${item.menu_id})">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = cartHtml;
            document.getElementById('display-total').innerText = total.toLocaleString('id-ID');
            
            // Enable button if cart is not empty
            submitBtn.disabled = false;
            submitBtn.className = "mt-6 w-full py-3 px-4 rounded-md text-white font-bold text-lg transition shadow-md bg-orange-500 hover:bg-orange-600 active:bg-orange-700 cursor-pointer";
            
            calculateChange();
        }

        function calculateChange() {
            let total = 0;
            cart.forEach(item => {
                total += item.price * item.quantity;
            });

            const paymentInput = document.getElementById('payment_amount');
            const changeDisplay = document.getElementById('display-change');
            const changeContainer = changeDisplay.parentElement;

            // Reset to green zero if input is empty
            if (!paymentInput || paymentInput.value === undefined || paymentInput.value.trim() === '') {
                changeDisplay.innerText = '0';
                changeContainer.className = "text-xl text-green-600";
                return;
            }

            const payment = parseFloat(paymentInput.value) || 0;
            const change = payment - total;

            if (change < 0) {
                // Negative change formatting
                changeDisplay.innerText = "-" + Math.abs(change).toLocaleString('id-ID');
                changeContainer.className = "text-xl text-red-600";
            } else {
                // Zero or positive change formatting
                changeDisplay.innerText = change.toLocaleString('id-ID');
                changeContainer.className = "text-xl text-green-600";
            }
        }

        // Validate on form submission
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            let total = 0;
            cart.forEach(item => {
                total += item.price * item.quantity;
            });

            const paymentInput = document.getElementById('payment_amount');
            const payment = parseFloat(paymentInput.value) || 0;

            if (payment < total) {
                e.preventDefault(); // Abort submit
                openFundsModal(total, payment);
                return false;
            }
        });

        function openFundsModal(total, paid) {
            const modal = document.getElementById('insufficient-funds-modal');
            const card = document.getElementById('modal-card');
            const icon = card.querySelector('.animate-target');

            const deficit = total - paid;
            document.getElementById('modal-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('modal-paid').innerText = 'Rp ' + paid.toLocaleString('id-ID');
            document.getElementById('modal-deficit').innerText = 'Rp ' + deficit.toLocaleString('id-ID');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                card.classList.remove('opacity-0', 'scale-95');
                card.classList.add('opacity-100', 'scale-100');
                
                if (icon) {
                    icon.classList.add('animate-shake');
                    setTimeout(() => {
                        icon.classList.remove('animate-shake');
                    }, 600);
                }
            }, 10);
        }

        function closeFundsModal() {
            const modal = document.getElementById('insufficient-funds-modal');
            const card = document.getElementById('modal-card');

            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            card.classList.remove('opacity-100', 'scale-100');
            card.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                const paymentInput = document.getElementById('payment_amount');
                if (paymentInput) {
                    paymentInput.focus();
                    paymentInput.select();
                }
            }, 300);
        }

        function openSuccessModal(total, paid, change) {
            const modal = document.getElementById('transaction-success-modal');
            const card = document.getElementById('success-modal-card');

            document.getElementById('success-modal-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('success-modal-paid').innerText = 'Rp ' + paid.toLocaleString('id-ID');
            document.getElementById('success-modal-change').innerText = 'Rp ' + change.toLocaleString('id-ID');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                card.classList.remove('opacity-0', 'scale-95');
                card.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeSuccessModal() {
            const modal = document.getElementById('transaction-success-modal');
            const card = document.getElementById('success-modal-card');

            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            card.classList.remove('opacity-100', 'scale-100');
            card.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                // Reset inputs and cart
                cart = [];
                renderCart();
                const paymentInput = document.getElementById('payment_amount');
                if (paymentInput) {
                    paymentInput.value = '';
                }
                calculateChange();
            }, 300);
        }
    </script>

    <!-- Custom CSS for warning and success modals -->
    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            15%, 45%, 75% { transform: translateX(-6px); }
            30%, 60%, 90% { transform: translateX(6px); }
        }
        .animate-shake {
            animation: shake 0.6s ease-in-out;
        }
        @keyframes popIn {
            0% { transform: scale(0.6); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes pulseSubtle {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .scale-animation {
            animation: popIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        .pulse-animation {
            animation: pulseSubtle 2s infinite ease-in-out;
        }
    </style>

    <!-- Custom Warning Modal Markup -->
    <div id="insufficient-funds-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <!-- Backdrop click closes modal -->
        <div class="absolute inset-0 cursor-default" onclick="closeFundsModal()"></div>
        
        <!-- Modal Card -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 ease-out border border-gray-100" id="modal-card">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-gray-50">
                <img src="{{ asset('images/logo-poci.png') }}" class="h-8 w-auto object-contain" alt="Logo Es Teh Poci">
                <button type="button" class="text-gray-400 hover:text-gray-600 transition focus:outline-none" onclick="closeFundsModal()">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Body -->
            <div class="p-6 text-center space-y-4">
                <!-- Red circle with cross icon -->
                <div class="flex justify-center">
                    <div class="h-20 w-20 rounded-full bg-red-100 border-4 border-red-500 flex items-center justify-center text-red-500 shadow-lg shadow-red-100/50 animate-target transition-transform duration-300">
                        <svg class="h-10 w-10 stroke-current" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
                
                <!-- Title -->
                <h3 class="text-2xl font-bold text-red-600 mt-4 leading-tight">
                    Maaf, Uang Anda Kurang!
                </h3>
                
                <!-- Details -->
                <div class="bg-red-50/50 rounded-xl p-4 border border-red-100 text-left text-sm space-y-2 mt-2">
                    <div class="flex justify-between text-gray-700">
                        <span>Total Belanja:</span>
                        <span class="font-bold font-mono text-gray-900" id="modal-total">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Uang Dibayar:</span>
                        <span class="font-bold font-mono text-gray-900" id="modal-paid">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-red-700 pt-2 border-t border-red-100/50">
                        <span>Kekurangan:</span>
                        <span class="font-bold font-mono text-red-600 text-base" id="modal-deficit">Rp 0</span>
                    </div>
                </div>
                
                <p class="text-xs text-gray-500 leading-relaxed px-4">
                    Mohon periksa kembali nominal pembayaran Anda sebelum memproses transaksi.
                </p>
            </div>
            
            <!-- Footer Button -->
            <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-center">
                <button type="button" class="w-full py-3 px-4 bg-white border border-red-300 text-red-700 hover:bg-red-50 font-bold rounded-xl shadow-sm transition active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" onclick="closeFundsModal()">
                    Periksa Kembali
                </button>
            </div>
        </div>
    </div>

    <!-- Custom Success Modal Markup -->
    <div id="transaction-success-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <!-- Backdrop click closes modal -->
        <div class="absolute inset-0 cursor-default" onclick="closeSuccessModal()"></div>
        
        <!-- Modal Card -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 ease-out border border-gray-100" id="success-modal-card">
            
            <!-- Body -->
            <div class="p-6 text-center space-y-4 pt-10">
                <!-- Green Circle with Checkmark Icon -->
                <div class="flex justify-center">
                    <div class="h-20 w-20 rounded-full bg-emerald-100 border-4 border-emerald-500 flex items-center justify-center text-emerald-500 shadow-lg shadow-emerald-100/50 scale-animation pulse-animation">
                        <svg class="h-12 w-12 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                
                <!-- Title -->
                <h3 class="text-2xl font-bold text-emerald-600 mt-4 leading-tight">
                    Pembayaran Berhasil!
                </h3>
                
                <!-- Details -->
                <div class="bg-emerald-50/40 rounded-xl p-4 border border-emerald-100 text-left text-sm space-y-2 mt-2">
                    <div class="flex justify-between text-gray-700">
                        <span>Total Belanja:</span>
                        <span class="font-bold font-mono text-gray-900" id="success-modal-total">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Uang Dibayar:</span>
                        <span class="font-bold font-mono text-gray-900" id="success-modal-paid">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-emerald-700 pt-2 border-t border-emerald-100/50">
                        <span>Uang Kembalian:</span>
                        <span class="font-bold font-mono text-emerald-600 text-base" id="success-modal-change">Rp 0</span>
                    </div>
                </div>
                
                <p class="text-xs text-gray-500 leading-relaxed px-4">
                    Data transaksi telah disimpan dan stok bahan baku telah diperbarui otomatis.
                </p>
            </div>
            
            <!-- Footer Button -->
            <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-center">
                <button type="button" class="w-full py-3 px-4 bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition active:scale-[0.98] focus:outline-none" onclick="closeSuccessModal()">
                    Transaksi Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Trigger Modal Sukses dari Session Laravel -->
    @if (session('success') && session('tx_total') !== null)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            openSuccessModal(
                {{ session('tx_total') }},
                {{ session('tx_paid') }},
                {{ session('tx_change') }}
            );
        });
    </script>
    @endif
</x-app-layout>

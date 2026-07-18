<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Show checkout / kasir screen.
     */
    public function index()
    {
        $menus = Menu::orderBy('name')->get();
        return view('checkout.index', compact('menus'));
    }

    /**
     * Store transaction and deduct inventories.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_amount' => 'required|numeric|min:0',
        ]);

        // Calculate total amount from requested items
        $total_price = 0;
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $itemData) {
                if (isset($itemData['menu_id']) && isset($itemData['quantity'])) {
                    $menu = Menu::find($itemData['menu_id']);
                    if ($menu) {
                        $total_price += $menu->price * intval($itemData['quantity']);
                    }
                }
            }
        }

        // Validate backend payment amount
        $paymentAmount = $request->input('payment_amount', $request->input('amount_paid', 0));
        if ($paymentAmount < $total_price) {
            return redirect()->back()->withInput()->with('error', 'Uang anda kurang!');
        }

        try {
            return DB::transaction(function () use ($request) {
                $totalAmount = 0;
                $detailsToCreate = [];

                // 1. Calculate totals and check stock requirements
                foreach ($request->items as $itemData) {
                    $menu = Menu::with('recipes.inventory')->findOrFail($itemData['menu_id']);
                    $qty = $itemData['quantity'];
                    $subtotal = $menu->price * $qty;
                    $totalAmount += $subtotal;

                    // Check stock for each ingredient in this menu
                    foreach ($menu->recipes as $recipe) {
                        $inventory = $recipe->inventory;
                        // Use lockForUpdate to avoid race conditions
                        $inventory = Inventory::lockForUpdate()->findOrFail($inventory->id);
                        $needed = $recipe->quantity * $qty;

                        if ($inventory->stock < $needed) {
                            throw new \Exception("Stok bahan baku '{$inventory->name}' tidak mencukupi untuk membuat '{$menu->name} ({$menu->size})' (Kurang: " . ($needed - $inventory->stock) . " {$inventory->unit}).");
                        }

                        // Save deduction in array to execute later
                        $detailsToCreate[] = [
                            'menu' => $menu,
                            'quantity' => $qty,
                            'price' => $menu->price,
                            'subtotal' => $subtotal,
                            'deductions' => [
                                'inventory' => $inventory,
                                'amount' => $needed
                            ]
                        ];
                    }

                    // Handle menu items with no recipe (just log, or charge normally)
                    if ($menu->recipes->isEmpty()) {
                        $detailsToCreate[] = [
                            'menu' => $menu,
                            'quantity' => $qty,
                            'price' => $menu->price,
                            'subtotal' => $subtotal,
                            'deductions' => null
                        ];
                    }
                }

                // Check if payment is sufficient
                $paymentAmount = $request->payment_amount;
                if ($paymentAmount < $totalAmount) {
                    throw new \Exception("Uang pembayaran tidak mencukupi. Total: Rp " . number_format($totalAmount, 0, ',', '.') . ", Dibayar: Rp " . number_format($paymentAmount, 0, ',', '.'));
                }

                $changeAmount = $paymentAmount - $totalAmount;

                // 2. Generate Invoice Number (e.g. TPC-20260717-0001)
                $dateStr = now()->format('Ymd');
                $lastTransaction = Transaction::where('invoice_number', 'like', "TPC-{$dateStr}-%")
                    ->orderBy('id', 'desc')
                    ->first();

                if ($lastTransaction) {
                    $lastSequence = intval(substr($lastTransaction->invoice_number, -4));
                    $nextSequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $nextSequence = '0001';
                }
                $invoiceNumber = "TPC-{$dateStr}-{$nextSequence}";

                // 3. Create Transaction Header
                $transaction = Transaction::create([
                    'invoice_number' => $invoiceNumber,
                    'user_id' => auth()->id(),
                    'total_amount' => $totalAmount,
                    'payment_amount' => $paymentAmount,
                    'change_amount' => $changeAmount,
                ]);

                // 4. Create details and apply deductions
                foreach ($detailsToCreate as $d) {
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'menu_id' => $d['menu']->id,
                        'quantity' => $d['quantity'],
                        'price' => $d['price'],
                        'subtotal' => $d['subtotal'],
                    ]);

                    if ($d['deductions']) {
                        $inv = $d['deductions']['inventory'];
                        $inv->stock -= $d['deductions']['amount'];
                        $inv->save();
                    }
                }

                return redirect()->route('checkout.index')->with([
                    'success' => "Transaksi {$invoiceNumber} berhasil!",
                    'tx_total' => $totalAmount,
                    'tx_paid' => $paymentAmount,
                    'tx_change' => $changeAmount,
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show transaction history (accessible by Owner, or Cashier depending on requirement).
     */
    public function history()
    {
        $transactions = Transaction::with('user', 'details.menu')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('checkout.history', compact('transactions'));
    }
}

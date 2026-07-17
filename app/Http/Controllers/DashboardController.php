<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // 1. Total Pendapatan Hari Ini
        $totalIncomeToday = Transaction::whereDate('created_at', $today)->sum('total_amount');

        // 2. Total Pendapatan Bulan Ini
        $totalIncomeMonth = Transaction::where('created_at', '>=', $startOfMonth)->sum('total_amount');

        // 3. Total Cup Terjual (Total quantity in details)
        $totalCupsSoldToday = TransactionDetail::whereHas('transaction', function ($q) use ($today) {
            $q->whereDate('created_at', $today);
        })->sum('quantity');

        $totalCupsSoldMonth = TransactionDetail::whereHas('transaction', function ($q) use ($startOfMonth) {
            $q->where('created_at', '>=', $startOfMonth);
        })->sum('quantity');

        // 4. Riwayat Transaksi (terbaru)
        $recentTransactions = Transaction::with('user')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // 5. Peringatan Stok Rendah (stock < min_stock)
        $lowStockItems = Inventory::whereColumn('stock', '<', 'min_stock')->get();

        return view('dashboard', compact(
            'totalIncomeToday',
            'totalIncomeMonth',
            'totalCupsSoldToday',
            'totalCupsSoldMonth',
            'recentTransactions',
            'lowStockItems'
        ));
    }
}

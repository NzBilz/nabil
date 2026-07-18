<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // 1. Calculate summary statistics for TODAY
        $today = now()->toDateString();
        $totalOmzet = Transaction::whereDate('created_at', $today)->sum('total_amount');
        $totalTransactions = Transaction::whereDate('created_at', $today)->count();
        $totalCups = TransactionDetail::whereHas('transaction', function ($q) use ($today) {
            $q->whereDate('created_at', $today);
        })->sum('quantity');

        // 2. Fetch latest transactions with eager loading to prevent N+1 query issue
        $transactions = Transaction::with(['user', 'details.menu'])
            ->latest()
            ->paginate(15);

        // 3. Group transactions by date for the last 7 days
        $dates = [];
        $fullDates = [];
        $totals = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates[] = $date->locale('id')->translatedFormat('d M');
            $fullDates[] = $date->toDateString();
            
            $dayTotal = Transaction::whereDate('created_at', $date->toDateString())->sum('total_amount');
            $totals[] = (float) $dayTotal;
        }

        return view('transactions.index', compact(
            'transactions', 
            'totalOmzet', 
            'totalTransactions', 
            'totalCups',
            'dates',
            'fullDates',
            'totals'
        ));
    }

    /**
     * Fetch statistics for a specific date (AJAX API).
     */
    public function metricsByDate(Request $request)
    {
        $date = $request->input('date');
        if (!$date) {
            return response()->json(['error' => 'Date parameter is required'], 400);
        }

        $omzet = Transaction::whereDate('created_at', $date)->sum('total_amount');
        $transaksi = Transaction::whereDate('created_at', $date)->count();
        $cup = TransactionDetail::whereHas('transaction', function ($q) use ($date) {
            $q->whereDate('created_at', $date);
        })->sum('quantity');

        return response()->json([
            'omzet' => (float) $omzet,
            'transaksi' => (int) $transaksi,
            'cup' => (int) $cup
        ]);
    }
}

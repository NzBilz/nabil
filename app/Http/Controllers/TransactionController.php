<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // 1. Calculate overall summary statistics
        $totalOmzet = Transaction::sum('total_amount');
        $totalTransactions = Transaction::count();
        $totalCups = TransactionDetail::sum('quantity');

        // 2. Fetch latest transactions with eager loading to prevent N+1 query issue
        $transactions = Transaction::with(['user', 'details.menu'])
            ->latest()
            ->paginate(15);

        return view('transactions.index', compact(
            'transactions', 
            'totalOmzet', 
            'totalTransactions', 
            'totalCups'
        ));
    }
}

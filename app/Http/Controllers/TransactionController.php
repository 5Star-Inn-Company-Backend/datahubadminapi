<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\transaction;
use App\Models\referandearn;
use App\Models\virtual_acct;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\tbl_airtime2cash;

class TransactionController extends Controller
{
    public function listalltransaction()
    {
        $transactions = transaction::all();

        return response()->json(['transactions' => $transactions]);
    }

    public function pendingtransaction()
    {
        $pendingTransactions = transaction::where('status', 0)->get();

        if ($pendingTransactions->isEmpty()) {
            return response()->json(['No pending transactions']);
        } else {
            return response()->json(['pending_transactions' => $pendingTransactions]);
        }
    }

    public function reversedtransaction()
    {
        $reversedTransactions = Transaction::where('status', 4)->get();

        if ($reversedTransactions->isEmpty()) {
            return response()->json(['No reversed transactions']);
        } else {
            return response()->json(['reversed_transactions' => $reversedTransactions]);
        }
    }

    public function searchtransaction(Request $request)
    {
        $request->validate([
            'search' => 'required|string', // Adjust the validation rule based on your criteria
        ]);

        $searchCriteria = $request->input('search');

        $searchResult = Transaction::where('title', 'like', '%' . $searchCriteria . '%')
            ->get();

        return response()->json(['search_result' => $searchResult]);
    }

    public function airtime2cash()

    {
        $airtime2cash = tbl_airtime2cash::all();
        if ($airtime2cash->isEmpty()) {
            return response()->json(['No airtime2cashs']);
        } else {
            return response()->json(['airtime2cashs' => $airtime2cash]);
        }
    }

    public function listvirtualacct()
    {
        // Retrieve all virtual accounts with their owners
        $virtualAccounts = virtual_acct::with('user')->get();

        if ($virtualAccounts->isEmpty()) {
            return response()->json(['No User with virtual accounts']);
        } else {
            // Return a JSON response with virtual accounts and their owners
            return response()->json(['virtual_accounts' => $virtualAccounts]);
        }
    }

    public function activeuser()
    {
        // Fetch active users (created between last month and current month)
        $lastMonth = Carbon::now()->subMonth();
        $currentMonth = Carbon::now();

        $activeUsers = Transaction::whereBetween('created_at', [$lastMonth, $currentMonth])->get();

        return response()->json(['active_users' => $activeUsers]);
    }

    public function dormantuser()
    {
        // Fetch dormant users (not created between current month and last month)
        $lastMonth = Carbon::now()->subMonth();
        $currentMonth = Carbon::now();

        $dormantUsers = Transaction::whereNotBetween('created_at', [$lastMonth, $currentMonth])->get();

        return response()->json(['dormant_users' => $dormantUsers]);
    }

    public function totalwalletcharge(Request $request)
    {
        $request->validate([
            'date' => 'required',
        ]);

        $date = $request->input('date');

        $totalWalletCharge = Transaction::where('type', 'debit')
            ->whereDate('created_at', $date)
            ->selectRaw('sum(amount) as total_charge, created_at')
            ->groupBy('created_at')
            ->get();

        return response()->json(['total_wallet_charge' => $totalWalletCharge]);
    }

    public function totalwalletfund(Request $request)
    {
        $request->validate([
            'date' => 'required',
        ]);

        $date = $request->input('date');

        $totalWalletFunding = Transaction::where('type', 'credit')
            ->whereDate('created_at', $date)
            ->selectRaw('sum(amount) as total_funding, created_at')
            ->groupBy('created_at')
            ->get();

        return response()->json(['total_wallet_funding' => $totalWalletFunding]);
    }

    public function totalsumtransaction()
    {
        $totalSumTransaction = Transaction::sum('amount');

        return response()->json(['total_sum_transaction' => $totalSumTransaction]);
    }

    public function totalcounttransaction()
    {
        $totalCountTransaction = Transaction::count();

        return response()->json(['total_count_transaction' => $totalCountTransaction]);
    }

    public function transactiontype()
    {
        $transactionTypes = Transaction::pluck('title')->unique()->toArray();

        return response()->json(['transaction_types' => $transactionTypes]);
    }

    public function referandearn(Request $request)
    {
        $request->validate([
            'refer' => 'required',
            'amount_to_earn' => 'required|numeric',
        ]);

        referandearn::create([
            'refer' => $request->input('refer'),
            'amount_to_earn' => $request->input('amount_to_earn'),
        ]);

        return response()->json(['message' => 'Refer and Earn entry added successfully']);
    }
}

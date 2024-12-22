<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\transaction;
use App\Models\referandearn;
use App\Models\virtual_acct;
use Illuminate\Http\Request;
use App\Models\funding_config;
use Illuminate\Support\Carbon;
use App\Models\tbl_airtime2cash;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function listalltransaction()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                $transactions = transaction::with('user')->latest()->get();

                return response()->json(['transactions' => $transactions]);
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function edittransaction(Request $request, $id)
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                // Check if the user exists
                $transaction = transaction::find($id);

                if (!$transaction) {
                    return response()->json(['error' => 'Id not found'], 404);
                }

                if($request->status == 4){
                    return $this->reversetranSF($transaction->reference);
                }

                // Get the fields and values from the request
                $updateFields = $request->only(['status']);

                // Update the user details based on the specified fields
                $transaction->fill($updateFields);
                if ($transaction->save()) {
                    // Return a JSON response with the modified user details
                    return response()->json(['message' => 'Transaction status updated successfully!', 'user' => $transaction]);
                } else {
                    // Return a JSON response with the modified user details
                    return response()->json(['message' => 'Unable to Modify transaction status']);
                }
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function pendingtransaction()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                $pendingTransactions = transaction::where('status', 0)->with('user')->latest()->get();

                if ($pendingTransactions->isEmpty()) {
                    return response()->json([
                        'data' => $pendingTransactions,
                        'message' => 'No pending transactions'
                    ]);
                } else {
                    return response()->json([
                        'data' => $pendingTransactions
                    ], 200);
                }
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function reversedtransaction()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                $reversedTransactions = Transaction::where('status', 4)->with('user')->latest()->get();

                if ($reversedTransactions->isEmpty()) {
                    return response()->json(['No reversed transactions']);
                } else {
                    return response()->json(['reversed_transactions' => $reversedTransactions]);
                }
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }
    public function reversetran(Request $request)
    {
        $reference = $request->input('reference');
        $transaction = Transaction::where('reference', $reference)->first();

        if ($transaction) {
            $user_id = $transaction->user_id;
            $amount = $transaction->amount;

            // $wallet = Wallet::where('user_id', $user_id)->first();
            $wallet=Wallet::where([['user_id',$user_id], ['name','wallet']])->first();

            if ($wallet) {
                $newbalance = $wallet->balance + $amount; // Add the amount back to the balance
                $wallet->update([
                    'balance' => $newbalance,
                ]);

                $transaction->update([
                    'status' => 4,
                ]);


                Transaction::create([
                    'user_id' => $user_id,
                    'title' => 'Transaction Reversal',
                    'amount' => $transaction->amount,
                    'status' => 1,
                    "transaction_type" => "reversal",
                    "remark" => "Reversal of " . $reference,
                    'type' => 'credit',
                    'reference' => $reference."_reversal",
                    "prev_balance" => $wallet->balance,
                    "new_balance" => $newbalance,
                    "server" => '0',
                ]);

                return response()->json(['message' => 'Transaction reversed successfully']);
            } else {
                return response()->json(['message' => 'Wallet not found for the user'], 404);
            }
        } else {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
    }

    public function reversetranSF($reference)
    {
        $transaction = Transaction::where('reference', $reference)->first();

        if ($transaction) {
            $user_id = $transaction->user_id;
            $amount = $transaction->amount;

            // $wallet = Wallet::where('user_id', $user_id)->first();
            $wallet=Wallet::where([['user_id',$user_id], ['name','wallet']])->first();

            if ($wallet) {
                $balance = $wallet->balance; // Add the amount back to the balance
                $wallet->update([
                    'balance' => $balance  + $amount,
                ]);

                $transaction->update([
                    'status' => 4,
                ]);


                Transaction::create([
                    'user_id' => $user_id,
                    'title' => 'Transaction Reversal',
                    'amount' => $transaction->amount,
                    'status' => 1,
                    "transaction_type" => "reversal",
                    "remark" => "Reversal of " . $reference,
                    'type' => 'credit',
                    'reference' => $reference."_reversal",
                    "prev_balance" => $balance,
                    "new_balance" => $wallet->balance,
                    "server" => '0',
                ]);

                return response()->json(['message' => 'Transaction reversed successfully']);
            } else {
                return response()->json(['message' => 'Wallet not found for the user'], 404);
            }
        } else {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
    }


    public function searchtransaction(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                $request->validate([
                    'search' => 'required|string', // Adjust the validation rule based on your criteria
                ]);

                $searchCriteria = $request->input('search');

                $searchResult = Transaction::where('reference', 'like', '%' . $searchCriteria . '%')->orwhere('title', 'like', '%' . $searchCriteria . '%')->orwhere('recipient', 'like', '%' . $searchCriteria . '%')->get();

                return response()->json(['search_result' => $searchResult]);
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function airtime2cash()

    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                $airtime2cash = tbl_airtime2cash::with('user')->get();
                if ($airtime2cash->isEmpty()) {
                    return response()->json(['No airtime2cashs']);
                } else {
                    $airtime = $airtime2cash->map(function ($item) {
                        return [
                            // 'user_email' => $item->user->email,
                            // 'user_phone' => $item->user->phone,
                            'airtime2cash' => $item,
                        ];
                    });

                    return response()->json(['airtime2cashs' => $airtime]);
                }
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function airtime2cashstatus(Request $request, $id)
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                try {

                    // Validate the incoming request
                    $request->validate(['status' => 'required']);

                    $acct = tbl_airtime2cash::find($id);
                    if (!$acct) {
                        return response()->json(['message' => 'Id not found'], 200);
                    }

                    $status = $request->input('status');

                    if ($status == '1') {
                        $acct->status = 'Successful';

                        $acct->save();
                    } elseif ($status == '2') {
                        $acct->status = 'Pending';

                        $acct->save();
                    } elseif ($status == '0') {
                        $acct->status = 'Failed';

                        $acct->save();
                    }

                    return response()->json(['message' => 'Airtime2cash status updated successfully'], 200);
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Failed to update Airtime2cash status'], 500);
                }
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not Authorize to carry out this action."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function listvirtualacct()
    {
        // Retrieve all virtual accounts with their owners
        $virtualAccounts = virtual_acct::with('user')->latest()->get();

        if ($virtualAccounts->isEmpty()) {
            return response()->json(['No User with virtual accounts']);
        } else {
            // Return a JSON response with virtual accounts and their owners
            return response()->json(['virtual_accounts' => $virtualAccounts]);
        }
    }

    public function deactivateacct(Request $request, $id)
    {

        try {

            // Validate the incoming request
            $request->validate(['status' => 'required']);

            $acct = virtual_acct::find($id);

            $status = $request->input('status');

            if ($status == '0') {
                $acct->status = 'deactivate';

                $acct->save();
            } else {
                $acct->status = 'active';

                $acct->save();
            }

            return response()->json(['message' => 'Account status updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update account status'], 500);
        }
    }

    public function activeuser()
    {
        // Fetch active users (created between last month and current month)
        $lastMonth = Carbon::now()->subMonth();
        $currentMonth = Carbon::now();

        $activeUsers = Transaction::whereBetween('created_at', [$lastMonth, $currentMonth])->select('user_id')->distinct()->get();
        $userDetails = [];
        foreach ($activeUsers as $transaction) {
            $userId = $transaction->user_id;
            $userDetails = User::find($userId);

            // Store user details in the array if the user is not null

            if($userDetails) {
                $active_users[] = [
                    // 'transaction_details' => $transaction,
                    'user_details' => $userDetails,
                ];
            }
        }

        return response()->json([
            'active_users' =>  $active_users
        ]);
    }

    public function dormantuser()
    {
        // Fetch dormant users (not created between current month and last month)
        $lastMonth = Carbon::now()->subMonth();
        $currentMonth = Carbon::now();

        $dormantUsersTransactions = Transaction::whereNotBetween('created_at', [$lastMonth, $currentMonth])->select('user_id')->distinct()->get();

        $dormantUsers = [];

        foreach ($dormantUsersTransactions as $transaction) {
            $userId = $transaction->user_id;
            $userDetails = User::find($userId);

            // Store user details in the array if the user is not null

            if($userDetails) {
                $dormantUsers[] = [
                    // 'transaction_details' => $transaction,
                    'user_details' => $userDetails,
                ];
            }
        }

        return response()->json(['dormant_users' => $dormantUsers]);
    }


    public function totalwalletcharge(Request $request)
    {

        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
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
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function totalcharge()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {

                $totalWalletCharge = Transaction::where('type', 'debit')->sum('amount');

                return response()->json(['total_wallet_charge' => $totalWalletCharge]);
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function totalwalletfund(Request $request)
    {

        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
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
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function totalfund()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {

                $totalWalletFunding = Transaction::where('type', 'credit')->sum('amount');

                return response()->json(['total_wallet_funding' => $totalWalletFunding]);
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function totalsumtransaction()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                $totalSumTransaction = Transaction::sum('amount');

                return response()->json(['total_sum_transaction' => $totalSumTransaction]);
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function totalcounttransaction()
    {

        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                $totalCountTransaction = Transaction::count();

                return response()->json(['total_count_transaction' => $totalCountTransaction]);
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function transactiontype()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                $transactionTypes = Transaction::pluck('title');

                return response()->json([
                    'transaction_types' => $transactionTypes
                ]);
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function referandearn(Request $request)
    {

        if (Auth::check()) {

            if (Auth::user()->role_id == 1) {

                // Validate the incoming request
                $request->validate([
                    'referer_phone' => 'required',
                    'amount_to_earn' => 'required',
                ]);


                $refer = new referandearn();
                $refer->referer_phone = $request->referer_phone;
                $refer->amount_to_earn = $request->amount_to_earn;
                //  dd($refer->referer_phone);
                $refer->save();

                return response()->json(['message' => 'Refer and Earn entry added successfully']);
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function referelist()
    {
        $referes = User::where('referer_id', '!=', null)->with('referee')->latest()->get();

        return response()->json([
            'Referes' => $referes
        ]);
    }

    public function listconfig()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                $config = funding_config::all();

                return response()->json([
                    'data' => $config
                ], 200);
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all Referes"
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }

    public function modifyconfig(Request $request, $id)
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                // Check if the user exists
                $funding = funding_config::find($id);

                if (!$funding) {
                    return response()->json(['error' => 'User not found'], 404);
                }

                // Get the fields and values from the request
                $updateFields = $request->only(['name', 'charges', 'description', 'ppkey']);

                // Update the user details based on the specified fields
                $funding->fill($updateFields);
                if ($funding->save()) {
                    // Return a JSON response with the modified user details
                    return response()->json(['message' => 'Funding config modified successfully', 'user' => $funding]);
                } else {
                    // Return a JSON response with the modified user details
                    return response()->json(['message' => 'Unable to Modify User details']);
                }
            } else {
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        } else {
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
    }
}

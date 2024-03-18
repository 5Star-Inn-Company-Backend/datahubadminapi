<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            // Add other validation rules as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 422);
        }
        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->dob = $request->dob;
        $user->email = $request->email;
        $user->bvn = $request->bvn;
        $user->bank_code = $request->bank_code;
        $user->account_name = $request->account_name;
        $user->role_id = 1;

        $user->password = Hash::make($request->password);
        $user->save();
        if ($user->save()) {

            return response()->json([
                'status' => true,
                "message" => "Registration successful",
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                "message" => "Unable to Register User",
            ], 422);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required",
            "password" => "required",
        ]);

        $password = $request->password;
        $email = $request->email;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = $request->user();

            if ($user->role_id != 1) {
                return response()->json([
                    "status" => false,
                    "message" => "Unauthorized credentials",
                ], 401);
            }
            $tokenresult = $user->createToken('admin');
            $token = $tokenresult->plainTextToken;
            $expires_at = Carbon::now()->addweeks(1);
            return response()->json([
                'status' => true,
                "data" => [
                    "user" => Auth::user(),
                    "access_token" => $token,
                    "token_type" => "Bearer",
                    "expires_at" => $expires_at,


                ]
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Wrong password or email address",
            ], 401);
        }
    }

    //list all users
    public function listusers()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                // Fetch all users from the 'users' table
                $users = User::all();

                // Extract names from users
                $userNames = $users->pluck('lastname');


                // Return a JSON response with user names
                return response()->json(['users' => $users]);
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

    // Search users based on a criteria
    public function searchusers(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                // Get the search term from the request
                $searchTerm = $request->input('search');

                // Search users based on the criteria (you can modify this query based on your requirements)
                $users = User::where('firstname', 'like', '%' . $searchTerm . '%')->orwhere('lastname', 'like', '%' . $searchTerm . '%')->get();

                // Extract names from the search result
                // Extract names from the search result
                $names = $users->map(function ($user) {
                    return $user->firstname . ' ' . $user->lastname;
                });

                // $firstNames = $users->pluck('firstname');
                // $lastName = $users->pluck('lastname');

                // Return a JSON response with the search result
                return response()->json([
                    'search_result' => $users,
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

    // Modify user details
    public function modifyuser(Request $request, $userid)
    {

        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                // Check if the user exists
                $user = User::find($userid);

                if (!$user) {
                    return response()->json(['error' => 'User not found'], 404);
                }

                // Get the fields and values from the request
                $updateFields = $request->only(['firstname', 'lastname', 'address', 'phone', 'gender', 'dob', 'email', 'bvn', 'bank_code', 'account_name', 'account_number']);

                // Update the user details based on the specified fields
                $user->fill($updateFields);
                if ($user->save()) {
                    // Return a JSON response with the modified user details
                    return response()->json(['message' => 'User details modified successfully', 'user' => $user]);
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

    // Suspend user
    public function suspenduser(Request $request, $userId)
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                // Validate the request parameters
                $request->validate([
                    'reason' => 'nullable|string',
                ]);

                // Check if the user exists
                $user = User::find($userId);

                if (!$user) {
                    return response()->json(['error' => 'User not found'], 404);
                }

                // Suspend the user by updating the status to 0
                $user->status = 0;

                // Store the reason for suspension if provided
                $reason = $request->input('reason');
                if ($reason) {
                    $user->status_reason = $reason;
                }

                // Save the changes
                $user->save();

                // Return a JSON response indicating the user has been suspended
                return response()->json(['message' => 'User suspended successfully', 'user' => $user]);
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

    // Retrieve user transactions
    public function usertransaction($userid)
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                // Check if the user exists
                $user = User::find($userid);

                if (!$user || !$user->exists) {
                    return response()->json(['error' => 'User not found'], 404);
                }

                // Retrieve transactions for the specific user
                $transactions = Transaction::where('user_id', $userid)->get();

                if ($transactions->isEmpty()) {
                    return response()->json(['message' => 'User transactions not found'], 404);
                }

                // Return a JSON response with user transactions
                return response()->json(['user_id' => $userid, 'transactions' => $transactions]);
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

    public function credituser(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required',
            'amount' => 'required|numeric',
            'description' => 'required',
        ]);

        $user = User::where('id', Auth::user()->id)->pluck('id');
        if ($user) {
            // dd($user);
            $wallet = Wallet::where('user_id', $user)->first();
            if ($wallet) {
                $balance =  $wallet->balance + $request->amount;
                $wallet->update([
                    'balance' => $balance
                ]);
                return response()->json(['message' => 'User credited successfully'], 200);
            } else {
                return response()->json([
                    'message' => 'User Wallet not Found'

                ]);
            }
        } else {
            return response()->json([
                'message' => 'User not Found'

            ]);
        }
    }

    public function debituser(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required',
            'amount' => 'required|numeric',
            'description' => 'required',
        ]);

        $user = User::where('id', Auth::user()->id)->pluck('id');
        if ($user) {
            // dd($user);
            $wallet = Wallet::where('user_id', $user)->first();
            if ($wallet) {
                $balance =  $wallet->balance - $request->amount;
                $wallet->update([
                    'balance' => $balance
                ]);
                return response()->json(['message' => 'User Debited successfully'], 200);
            } else {
                return response()->json([
                    'message' => 'User Wallet not Found'

                ]);
            }
        } else {
            return response()->json([
                'message' => 'User not Found'

            ]);
        }
    }
}

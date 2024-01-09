<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\transaction;
use Illuminate\Http\Request;

class UserController extends Controller
{

    //list all users
    public function listusers()
    {
        // Fetch all users from the 'users' table
        $users = User::all();

        // Extract names from users
        $userNames = $users->pluck('lastname');


        // Return a JSON response with user names
        return response()->json(['users' => $userNames]);
    }

    // Search users based on a criteria
    public function searchusers(Request $request)
    {
        // Get the search term from the request
        $searchTerm = $request->input('search');

        // Search users based on the criteria (you can modify this query based on your requirements)
        $users = User::where('firstname', 'like', '%' . $searchTerm . '%')->get();

        // Extract names from the search result
        $userNames = $users->pluck('firstname');

        // Return a JSON response with the search result
        return response()->json(['search_result' => $userNames]);
    }

    // Modify user details
    public function modifyuser(Request $request, $userid)
    {
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
    }

    // Suspend user
    public function suspenduser(Request $request, $userId)
    {
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
    }

    // Retrieve user transactions
    public function usertransaction($userid)
    {
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
    }
}

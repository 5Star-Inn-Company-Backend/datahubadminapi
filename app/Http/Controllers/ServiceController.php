<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_airtime2cash;
use Illuminate\Support\Facades\Auth;
use App\Models\tbl_serverconfig_data;
use App\Models\tbl_serverconfig_airtime;
use App\Models\tbl_serverconfig_betting;
use App\Models\tbl_serverconfig_cabletv;
use App\Models\tbl_serverconfig_electricity;

class ServiceController extends Controller
{
    public function modifyairtime(Request $request, $id)
    {

        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
            
        // Validate the request parameters
        $request->validate([
            'status' => 'required',
        ]);

        // Check if the id exists
        $airtime = tbl_serverconfig_airtime::find($id);

        if (!$airtime) {
            return response()->json(['error' => 'Id not found'], 404);
        }

        // Modify the status to 0 or 1
        $airtime->status = $request->input('status');

        // Return a JSON response indicating that it has been modified
        return response()->json(['message' => 'You have successfully modified Airtime plan', 'user' =>  $airtime]);
            }else{
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        }else{
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }

    }

    public function modifydata(Request $request, $id)
    {

        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                 // Validate the request parameters
        $request->validate([
            'status' => 'required',
        ]);

        // Check if the id exists
        $data = tbl_serverconfig_data::find($id);

        if (!$data) {
            return response()->json(['error' => 'Id not found'], 404);
        }

        // Modify the status to 0 or 1
        $data->status = $request->input('status');

        // Return a JSON response indicating that it has been modified
        return response()->json(['message' => 'You have successfully modified Data plan', 'Dataplan' =>  $data]);
            }else{
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        }else{
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }

   
    }

    public function modifytvplan(Request $request, $id)
    {

        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
              // Validate the request parameters
        $request->validate([
            'status' => 'required',
        ]);

        // Check if the id exists
        $tvplan = tbl_serverconfig_cabletv::find($id);

        if (!$tvplan) {
            return response()->json(['error' => 'Id not found'], 404);
        }

        // Modify the status to 0 or 1
        $tvplan->status = $request->input('status');

        // Return a JSON response indicating that it has been modified
        return response()->json(['message' => 'You have successfully modified Tv plan', 'Tvplans' =>  $tvplan]);
            }else{
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        }else{
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
      
    }

    public function modifyelectricity(Request $request, $id)
    {

        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
               // Validate the request parameters
               $request->validate([
                'status' => 'required',
            ]);
    
            // Check if the id exists
            $electricity = tbl_serverconfig_electricity::find($id);
    
            if (!$electricity) {
                return response()->json(['error' => 'Id not found'], 404);
            }
    
            // Modify the status to 0 or 1
            $electricity->status = $request->input('status');
    
            // Return a JSON response indicating that it has been modified
            return response()->json(['message' => 'You have successfully modified Electricity plan', 'Electricity plans' =>  $electricity]);
            }else{
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        }else{
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }

           
    }

    public function modifybetting(Request $request, $id)
    {

        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
             // Validate the request parameters
             $request->validate([
                'status' => 'required',
            ]);
    
            // Check if the id exists
            $electricity = tbl_serverconfig_betting::find($id);
    
            if (!$electricity) {
                return response()->json(['error' => 'Id not found'], 404);
            }
    
            // Modify the status to 0 or 1
            $electricity->status = $request->input('status');
    
            // Return a JSON response indicating that it has been modified
            return response()->json(['message' => 'You have successfully modified Betting plan', 'Betting plans' =>  $electricity]);
            }else{
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        }else{
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }
             
    }

    public function modifyairtime2cash(Request $request, $id)
    {

        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
            
              // Validate the request parameters
              $request->validate([
                'status' => 'required',
            ]);
    
            // Check if the id exists
            $airtime2cash = tbl_airtime2cash::find($id);
    
            if (!$airtime2cash) {
                return response()->json(['error' => 'Id not found'], 404);
            }
    
            // Modify the status to 0 or 1
            $airtime2cash->status = $request->input('status');
    
            // Return a JSON response indicating that it has been modified
            return response()->json(['message' => 'You have successfully modified Airtime2cash', 'Airtime2cash' =>  $airtime2cash]);
            }else{
                return response()->json([
                    "status" => "401",
                    "message" => "You are not allowed to view all users."
                ]);
            }
        }else{
            return response()->json([
                "status" => "200",
                "message" => "Unauthenticated"
            ]);
        }

    }
}

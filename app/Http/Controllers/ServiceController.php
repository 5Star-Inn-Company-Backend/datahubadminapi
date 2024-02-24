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
use App\Models\tbl_serverconfig_airtime2cash;
use App\Models\ownwebsite;

class ServiceController extends Controller
{
    public function listallairtime()
    {
        $airtime = tbl_serverconfig_airtime::all();
        return response()->json([
            "status" => 200,
            "data" => $airtime
            ]);
    }
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

            // Get the fields and values from the request
                $updateFields = $request->only(['network', 'discount', 'server', 'status']);

                // Update the user details based on the specified fields
                $airtime->fill($updateFields);
                
                if($airtime->save())
                {
                       // Return a JSON response indicating that it has been modified
                    return response()->json(['message' => 'You have successfully modified Airtime plan', 'user' =>  $airtime]);
                }

     
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


    public function listlldata()
    {
        $data = tbl_serverconfig_data::all();
        return response()->json([
            "status" => 200,
            "data" => $data
            ]);
    }
    public function modifydata(Request $request, $id)
    {

        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {


        // Check if the id exists
        $data = tbl_serverconfig_data::find($id);

        if (!$data) {
            return response()->json(['error' => 'Id not found'], 404);
        }

            // Get the fields and values from the request
                $updateFields = $request->only(['name', 'coded', '	server', 'category', 'amount', 'network', 'network_code','dataplan','plan_id','price','status']);

                // Update the user details based on the specified fields
                $data->fill($updateFields);
                  if ($data->save()) {
                    // Return a JSON response indicating that it has been modified
                return response()->json(['message' => 'You have successfully modified Data plan', 'Dataplan' =>  $data]);
                  }else{
                      return response()->json(['message' => 'Unable to modified Data plan']);
                  }
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
    
    public function listalltvplan()
    {
        $tvplan = tbl_serverconfig_cabletv::all();
        return response()->json([
            "status" => 200,
            "data" => $tvplan
            ]);
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

                // Get the fields and values from the request
                $updateFields = $request->only(['type','name', 'coded', 'code','price', 'discount', 'status', 'server']);

                // Update the user details based on the specified fields
                $tvplan->fill($updateFields);
                if($tvplan->save()){
                       // Return a JSON response indicating that it has been modified
                return response()->json(['message' => 'You have successfully modified Tv plan', 'Tvplans' =>  $tvplan]);
                }
     
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
    
    public function listelectricity()
    {
        $electricity = tbl_serverconfig_electricity::all();
        return response()->json([
            "status" => 200,
            "data" => $electricity
            ]);
    }

   public function modifyelectricity(Request $request, $id)
    {

        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                // Check if the id exists
                $electricity = tbl_serverconfig_electricity::find($id);

                if (!$electricity) {
                    return response()->json(['error' => 'Id not found'], 404);
                }

                // Get the fields and values from the request
                $updateFields = $request->only(['name', 'code', 'code10', 'discount', 'status', 'server']);

                // Update the user details based on the specified fields
                $electricity->fill($updateFields);
                if ($electricity->save()) {
                    // Return a JSON response with the modified user details
                    return response()->json(['message' => 'Electricity details modified successfully', 'Electricity' =>  $electricity]);
                } else {
                    // Return a JSON response with the modified user details
                    return response()->json(['message' => 'Unable to Electricity details']);
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

    
    public function listbetting()
    {
        $betting = tbl_serverconfig_betting::all();
        return response()->json([
            "status" => 200,
            "data" => $betting
            ]);
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
            $betting = tbl_serverconfig_betting::find($id);
    
            if (!$betting) {
                return response()->json(['error' => 'Id not found'], 404);
            }
    
             // Get the fields and values from the request
                $updateFields = $request->only(['name', 'code', 'discount', 'status', 'server']);

                // Update the user details based on the specified fields
                $betting->fill($updateFields);
                
                if($betting->save())
                {
                       // Return a JSON response indicating that it has been modified
                return response()->json(['message' => 'You have successfully modified Betting plan', 'Betting plans' =>  $betting]);  
                }
    
       
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
    
    public function listairtime2cash()
    {
        $airtime2cash = tbl_serverconfig_airtime2cash::all();
        return response()->json([
            "status" => 200,
            "data" => $airtime2cash
            ]);
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
            $airtime2cash = tbl_serverconfig_airtime2cash::find($id);
    
            if (!$airtime2cash) {
                return response()->json(['error' => 'Id not found'], 404);
            }
    
          // Get the fields and values from the request
                $updateFields = $request->only(['network', 'number', 'discount', 'status']);

                // Update the user details based on the specified fields
                $airtime2cash->fill($updateFields);
                
                if($airtime2cash->save())
                {
                             // Return a JSON response indicating that it has been modified
                return response()->json(['message' => 'You have successfully modified Airtime2cash', 'Airtime2cash' =>  $airtime2cash]);   
                }
    

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
    
    public function ownwebsite(Request $request)
    {
        
        // Validate input data
        $request->validate([
            'business_name' => 'required',
            'business_logo_url' => 'required',
            'website_address' => 'required',
            'business_phone_no' => 'required',
        ]);
        
         $ownWebsite = ownwebsite::create([
            'business_name' => $request->input('business_name'),
            'business_logo_url' => $request->input('business_logo_url'),
            'website_address' => $request->input('website_address'),
            'business_phone_no' => $request->input('business_phone_no'),
        ]);
        if($ownWebsite){
             return response()->json([
            'message' => 'Saved successfully',
        ], 200);
        
        }else{
             return response()->json([
            'message' => 'Unable to Save',
        ], 401);
        }
        
    }
}

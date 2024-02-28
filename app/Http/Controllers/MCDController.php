<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MCDController extends Controller
{
    public function balance()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('MCD_URL').'/my-balance',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.env('MCD_TOKEN')
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $rep=json_decode($response,true);

        if($rep['success'] == 1){
            return response()->json([
                "status" => 200,
                "data" => $rep['data']
            ]);
        }else{
            return response()->json([
                "status" => 401,
                'message' => 'Error while fetching info',
            ]);
        }

    }

    public function transactionList()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('MCD_URL').'/my-transactions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.env('MCD_TOKEN')
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $rep=json_decode($response,true);

        if($rep['success'] == 1){
            return response()->json([
                "status" => 200,
                "data" => $rep['data']
            ]);
        }else{
            return response()->json([
                "status" => 401,
                'message' => 'Error while fetching info',
            ]);
        }

    }

    public function commissionsList()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('MCD_URL').'/my-commissions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.env('MCD_TOKEN')
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $rep=json_decode($response,true);

        if($rep['success'] == 1){
            return response()->json([
                "status" => 200,
                "data" => $rep['data']
            ]);
        }else{
            return response()->json([
                "status" => 401,
                'message' => 'Error while fetching info',
            ]);
        }

    }

    public function withdrawalList()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('MCD_URL').'/my-withdrawals',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.env('MCD_TOKEN')
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $rep=json_decode($response,true);

        if($rep['success'] == 1){
            return response()->json([
                "status" => 200,
                "data" => $rep['data']
            ]);
        }else{
            return response()->json([
                "status" => 401,
                'message' => 'Error while fetching info',
            ]);
        }

    }

    public function bankList()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('MCD_URL').'/banklist',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.env('MCD_TOKEN')
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $rep=json_decode($response,true);

        if($rep['success'] == 1){
            return response()->json([
                "status" => 200,
                "data" => $rep['data']
            ]);
        }else{
            return response()->json([
                "status" => 401,
                'message' => 'Error while fetching info',
            ]);
        }

    }

    public function verifyBank(Request $request)
    {

        $input = $request->all();
        $rules = array(
            "code" => "required",
            "accountnumber" => "required|min:10",
        );

        $validator = Validator::make($input, $rules);

        if (!$validator->passes()) {
            return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all()), 'error' => $validator->errors()->all()]);
        }

        $payload='{
    "accountnumber": "'.$input['accountnumber'].'",
    "code": "'.$input['code'].'"
}';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('MCD_URL').'/verifyBank',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.env('MCD_TOKEN')
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $rep=json_decode($response,true);

        if($rep['success'] == 1){
            return response()->json([
                "status" => 200,
                "data" => $rep['data']
            ]);
        }else{
            return response()->json([
                "status" => 401,
                'message' => 'Error while fetching info',
            ]);
        }

    }

    public function makeWithdrawal(Request $request)
    {

        $input = $request->all();
        $rules = array(
            "amount" => "required",
            "account_number" => "required|min:10",
            "bank_code" => "required|min:2",
            "bank" => "required|min:3",
            "wallet" => "required",
        );

        $validator = Validator::make($input, $rules);

        if (!$validator->passes()) {
            return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all()), 'error' => $validator->errors()->all()]);
        }

        $payload='{
    "amount": "'.$input['amount'].'",
    "account_number": "'.$input['account_number'].'",
    "bank_code": "'.$input['bank_code'].'",
    "bank": "'.$input['bank'].'",
    "wallet": "'.$input['wallet'].'",
    "ref":"'.rand().'"
}';

        Log::info("=====MCDmake-withdrawal====${payload}");


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('MCD_URL').'/make-withdrawal',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.env('MCD_TOKEN')
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $rep=json_decode($response,true);

        if($rep['success'] == 1){
            return response()->json([
                "status" => 200,
                "data" => $rep['message']
            ]);
        }else{
            return response()->json([
                "status" => 401,
                'message' => 'Error while fetching info',
            ]);
        }

    }

}

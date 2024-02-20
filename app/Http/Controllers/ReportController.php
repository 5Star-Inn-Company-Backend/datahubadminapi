<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\tbl_airtime2cash;
use Illuminate\Support\Facades\Auth;
use App\Models\tbl_serverconfig_data;
use App\Models\tbl_serverconfig_airtime;
use App\Models\tbl_serverconfig_betting;
use App\Models\tbl_serverconfig_cabletv;
use App\Models\tbl_serverconfig_electricity;
use App\Models\transaction;

class ReportController extends Controller
{
    public function getDailyReport(Request $request)
    {
        try {

            if (Auth::check()) {

                if (Auth::user()->role_id == 1) {
                    // Get the date parameter from the request
                    $requestedDate = $request->input('date');

                    // Validate the date format (you may want to customize the validation based on your needs)
                    if (!\DateTime::createFromFormat('Y-m-d', $requestedDate)) {
                        throw new \InvalidArgumentException('Invalid date format. Please provide a date in the format YYYY-MM-DD.');
                    }

                    $dataReport = transaction::where('transaction_type', 'data')->whereDate('created_at', $requestedDate)->get();

                    $airtimeReport = transaction::where('transaction_type', 'airtime')->whereDate('created_at', $requestedDate)->get();

                    $cableTVReport = transaction::where('transaction_type', 'cabletv')->whereDate('created_at', $requestedDate)->get();

                    $electricityReport = transaction::where('transaction_type', 'electricity')->whereDate('created_at', $requestedDate)->get();

                    $bettingReport = transaction::where('transaction_type', 'betting')->whereDate('created_at', $requestedDate)->get();

                    return response()->json([
                        'data' => $dataReport,
                        'airtime' => $airtimeReport,
                        'cabletv' => $cableTVReport,
                        'electricity' => $electricityReport,
                        'betting' => $bettingReport,
                    ]);
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
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                "status" => "400",
                "message" => $e->getMessage()
            ]);
        }
    }



    public function getMonthlyReport(Request $request)
    {
        try {

            if (Auth::check()) {
                if (Auth::user()->role_id == 1) {

                    // Get the date parameter from the request
                    $requestedMonth = $request->input('month');

                    if (!\DateTime::createFromFormat('m', $requestedMonth)) {
                        throw new \InvalidArgumentException('Invalid date format. Please provide a date in the format m.');
                    }

                    $dataReport = transaction::where('transaction_type', 'data')->whereMonth('created_at', $requestedMonth)->get();

                    $airtimeReport = transaction::where('transaction_type', 'airtime')->whereMonth('created_at', $requestedMonth)->get();

                    $cableTVReport = transaction::where('transaction_type', 'cabletv')->whereMonth('created_at', $requestedMonth)->get();

                    $electricityReport = transaction::where('transaction_type', 'electricity')->whereMonth('created_at', $requestedMonth)->get();

                    $bettingReport = transaction::where('transaction_type', 'betting')->whereMonth('created_at', $requestedMonth)->get();

                    return response()->json([
                        'data' => $dataReport,
                        'airtime' => $airtimeReport,
                        'cabletv' => $cableTVReport,
                        'electricity' => $electricityReport,
                        'betting' => $bettingReport,
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
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                "status" => "400",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function getYearlyReport(Request $request)
    {
        try {

            if (Auth::check()) {
                if (Auth::user()->role_id == 1) {

                    // Get the date parameter from the request
                    $requestedYear = $request->input('year');

                    // Validate the date format (you may want to customize the validation based on your needs)
                    if (!\DateTime::createFromFormat('Y', $requestedYear)) {
                        throw new \InvalidArgumentException('Invalid year format. Please provide a year in the format Y.');
                    }


                    $dataReport = transaction::where('transaction_type', 'data')->whereYear('created_at', $requestedYear)->get();

                    $airtimeReport = transaction::where('transaction_type', 'airtime')->whereYear('created_at', $requestedYear)->get();

                    $cableTVReport = transaction::where('transaction_type', 'cabletv')->whereYear('created_at', $requestedYear)->get();

                    $electricityReport = transaction::where('transaction_type', 'electricity')->whereYear('created_at', $requestedYear)->get();

                    $bettingReport = transaction::where('transaction_type', 'betting')->whereYear('created_at', $requestedYear)->get();

                    return response()->json([
                        'data' => $dataReport,
                        'airtime' => $airtimeReport,
                        'cabletv' => $cableTVReport,
                        'electricity' => $electricityReport,
                        'betting' => $bettingReport,
                    ]);
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
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                "status" => "400",
                "message" => $e->getMessage()
            ]);
        }
    }
}

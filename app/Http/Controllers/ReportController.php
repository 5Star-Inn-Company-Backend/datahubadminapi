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

class ReportController extends Controller
{
    public function getDailyReport()
{
    if (Auth::check()) {
        if (Auth::user()->role_id == 1) {
        
    $currentDate = Carbon::now()->toDateString();
    $previousDate = Carbon::yesterday()->toDateString();

    $dataReport = tbl_serverconfig_data::whereDate('created_at', $currentDate)
        ->orWhereDate('created_at', $previousDate)
        ->get();

    $airtimeReport = tbl_serverconfig_airtime::whereDate('created_at', $currentDate)
        ->orWhereDate('created_at', $previousDate)
        ->get();

    $cableTVReport = tbl_serverconfig_cabletv::whereDate('created_at', $currentDate)
        ->orWhereDate('created_at', $previousDate)
        ->get();

    $electricityReport = tbl_serverconfig_electricity::whereDate('created_at', $currentDate)
        ->orWhereDate('created_at', $previousDate)
        ->get();

    $bettingReport = tbl_serverconfig_betting::whereDate('created_at', $currentDate)
        ->orWhereDate('created_at', $previousDate)
        ->get();

    return response()->json([
        'data' => $dataReport,
        'airtime' => $airtimeReport,
        'cabletv' => $cableTVReport,
        'electricity' => $electricityReport,
        'betting' => $bettingReport,
    ]);
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


    public function getMonthlyReport()
{

    if (Auth::check()) {
        if (Auth::user()->role_id == 1) {
           $currentMonth = Carbon::now()->month;
    $previousMonth = $currentMonth - 1;

    $dataReport = tbl_serverconfig_data::whereMonth('created_at', $currentMonth)
        ->orWhereMonth('created_at', $previousMonth)
        ->get();

    $airtimeReport = tbl_serverconfig_airtime::whereMonth('created_at', $currentMonth)
        ->orWhereMonth('created_at', $previousMonth)
        ->get();

    $cableTVReport = tbl_serverconfig_cabletv::whereMonth('created_at', $currentMonth)
        ->orWhereMonth('created_at', $previousMonth)
        ->get();

    $electricityReport = tbl_serverconfig_electricity::whereMonth('created_at', $currentMonth)
        ->orWhereMonth('created_at', $previousMonth)
        ->get();

    $bettingReport = tbl_serverconfig_betting::whereMonth('created_at', $currentMonth)
        ->orWhereMonth('created_at', $previousMonth)
        ->get();

    return response()->json([
        'data' => $dataReport,
        'airtime' => $airtimeReport,
        'cabletv' => $cableTVReport,
        'electricity' => $electricityReport,
        'betting' => $bettingReport,
    ]);
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

    public function getYearlyReport()
    {

        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                $currentYear = Carbon::now()->year;
                $previousYear = $currentYear - 1;
            
                $dataReport = tbl_serverconfig_data::whereYear('created_at', $currentYear)
                    ->orWhereYear('created_at', $previousYear)
                    ->get();
            
                $airtimeReport = tbl_serverconfig_airtime::whereYear('created_at', $currentYear)
                    ->orWhereYear('created_at', $previousYear)
                    ->get();
            
                $cableTVReport = tbl_serverconfig_cabletv::whereYear('created_at', $currentYear)
                    ->orWhereYear('created_at', $previousYear)
                    ->get();
            
                $electricityReport = tbl_serverconfig_electricity::whereYear('created_at', $currentYear)
                    ->orWhereYear('created_at', $previousYear)
                    ->get();
            
                $bettingReport = tbl_serverconfig_betting::whereYear('created_at', $currentYear)
                    ->orWhereYear('created_at', $previousYear)
                    ->get();
            
                return response()->json([
                    'data' => $dataReport,
                    'airtime' => $airtimeReport,
                    'cabletv' => $cableTVReport,
                    'electricity' => $electricityReport,
                    'betting' => $bettingReport,
                ]);
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

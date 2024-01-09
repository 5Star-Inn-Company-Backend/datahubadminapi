<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//user modules routes
Route::get('allusers', [UserController::class, 'listusers']);
Route::post('searchuser', [UserController::class, 'searchusers']);
Route::post('modifyuser/{userid}', [UserController::class, 'modifyuser']);
Route::post('suspenduser/{userid}', [UserController::class, 'suspenduser']);
Route::get('usertransaction/{userid}', [UserController::class, 'usertransaction']);

//transaction modules
Route::get('listalltransaction', [TransactionController::class, 'listalltransaction']);
Route::get('pendingtransaction', [TransactionController::class, 'pendingtransaction']);
Route::get('reversedtransaction', [TransactionController::class, 'reversedtransaction']);
Route::post('searchtransaction', [TransactionController::class, 'searchtransaction']);
Route::get('airtime2cash', [TransactionController::class, 'airtime2cash']);
Route::get('listvirtualacct', [TransactionController::class, 'listvirtualacct']);
Route::get('activeuser', [TransactionController::class, 'activeuser']);
Route::get('dormantuser', [TransactionController::class, 'dormantuser']);
Route::post('totalwalletcharge', [TransactionController::class, 'totalwalletcharge']);
Route::post('totalwalletfund', [TransactionController::class, 'totalwalletfund']);
Route::get('totalsumtransaction', [TransactionController::class, 'totalsumtransaction']);
Route::get('totalcounttransaction', [TransactionController::class, 'totalcounttransaction']);
Route::get('transactiontype', [TransactionController::class, 'transactiontype']);
Route::post('referandearn', [TransactionController::class, 'referandearn']);

//service modules

Route::post('modifyairtime/{id}', [ServiceController::class, 'modifyairtime']);
Route::post('modifydata/{id}', [ServiceController::class, 'modifydata']);
Route::post('modifytvplan/{id}', [ServiceController::class, 'modifytvplan']);
Route::post('modifyelectricity/{id}', [ServiceController::class, 'modifyelectricity']);
Route::post('modifybetting/{id}', [ServiceController::class, 'modifybetting']);
Route::post('modifyairtime2cash/{id}', [ServiceController::class, 'modifyairtime2cash']);

//report modules

Route::get('dailyreport', [ReportController::class, 'getdailyreport']);
Route::get('monthlyreport', [ReportController::class, 'getmonthlyreport']);
Route::get('yearlyreport', [ReportController::class, 'getyearlyreport']);
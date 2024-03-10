<?php

use App\Http\Controllers\MCDController;
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

//auth
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function (){
//user modules routes
Route::get('allusers', [UserController::class, 'listusers']);
Route::post('searchuser', [UserController::class, 'searchusers']);
Route::post('modifyuser/{userid}', [UserController::class, 'modifyuser']);
Route::post('suspenduser/{userid}', [UserController::class, 'suspenduser']);
Route::get('usertransaction/{userid}', [UserController::class, 'usertransaction']);

//transaction modules
Route::get('listalltransaction', [TransactionController::class, 'listalltransaction']);
Route::post('edittransaction/{id}', [TransactionController::class, 'edittransaction']);
Route::get('pendingtransaction', [TransactionController::class, 'pendingtransaction']);
Route::get('reversedtransaction', [TransactionController::class, 'reversedtransaction']);
Route::post('searchtransaction', [TransactionController::class, 'searchtransaction']);
Route::get('airtime2cash', [TransactionController::class, 'airtime2cash']);
Route::post('airtime2cashstatus/{id}', [TransactionController::class, 'airtime2cashstatus']);
Route::get('listvirtualacct', [TransactionController::class, 'listvirtualacct']);
Route::post('deactivateacct/{id}', [TransactionController::class, 'deactivateacct']);

Route::get('activeuser', [TransactionController::class, 'activeuser']);
Route::get('dormantuser', [TransactionController::class, 'dormantuser']);
Route::post('totalwalletcharge', [TransactionController::class, 'totalwalletcharge']);
Route::get('totalcharge', [TransactionController::class, 'totalcharge']);
Route::post('totalwalletfund', [TransactionController::class, 'totalwalletfund']);
Route::get('totalfund', [TransactionController::class, 'totalfund']);
Route::get('totalsumtransaction', [TransactionController::class, 'totalsumtransaction']);
Route::get('totalcounttransaction', [TransactionController::class, 'totalcounttransaction']);
Route::get('transactiontype', [TransactionController::class, 'transactiontype']);
Route::post('referandearn', [TransactionController::class, 'referandearn']);
Route::get('referelist', [TransactionController::class, 'referelist']);
//modify funding config
Route::post('modifyconfig/{id}',[TransactionController::class, 'modifyconfig']);
Route::get('listconfig', [TransactionController::class, 'listconfig']);

//service modules

Route::post('modifyairtime/{id}', [ServiceController::class, 'modifyairtime']);
Route::post('modifydata/{id}', [ServiceController::class, 'modifydata']);
Route::post('modifytvplan/{id}', [ServiceController::class, 'modifytvplan']);
Route::post('modifyelectricity/{id}', [ServiceController::class, 'modifyelectricity']);
Route::post('modifybetting/{id}', [ServiceController::class, 'modifybetting']);
Route::post('modifyairtime2cash/{id}', [ServiceController::class, 'modifyairtime2cash']);
Route::get('listallairtime', [ServiceController::class, 'listallairtime']);
Route::get('listlldata', [ServiceController::class, 'listlldata']);
Route::get('listalltvplan', [ServiceController::class, 'listalltvplan']);
Route::get('listelectricity', [ServiceController::class, 'listelectricity']);
Route::get('listbetting', [ServiceController::class, 'listbetting']);
Route::get('listairtime2cash', [ServiceController::class, 'listairtime2cash']);


//report modules

Route::post('dailyreport', [ReportController::class, 'getdailyreport']);
Route::post('monthlyreport', [ReportController::class, 'getmonthlyreport']);
Route::post('yearlyreport', [ReportController::class, 'getyearlyreport']);

//own a website
Route::post('ownwebsite', [ServiceController::class, 'ownwebsite']);

Route::get('mcd-balance', [MCDController::class, 'balance']);
Route::get('mcd-banklist', [MCDController::class, 'banklist']);
Route::post('mcd-verifyBank', [MCDController::class, 'verifyBank']);
Route::post('mcd-makeWithdrawal', [MCDController::class, 'makeWithdrawal']);
Route::get('mcd-withdrawals', [MCDController::class, 'withdrawalList']);
Route::get('mcd-transactions', [MCDController::class, 'transactionList']);
Route::get('mcd-commissions', [MCDController::class, 'commissionsList']);

});


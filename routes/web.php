<?php

use App\Http\Controllers\GoldController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

//Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');


Route::group(['middleware' => 'auth'], function () {

    Route::get('investors', function () {
        return view('pages.investors');
    })->name('investors');

	Route::get('table-list', function () {
		return view('pages.table_list');
	})->name('table');

	Route::get('typography', function () {
		return view('pages.typography');
	})->name('typography');

	Route::get('icons', function () {
		return view('pages.icons');
	})->name('icons');

	Route::get('map', function () {
		return view('pages.map');
	})->name('map');

	Route::get('notifications', function () {
		return view('pages.notifications');
	})->name('notifications');

});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

	//app routes
    Route::get('users/list', [\App\Http\Controllers\UserController::class, 'getUsers'])->name('users.list');
    Route::get('users/changeUserStatus',[\App\Http\Controllers\UserController::class, 'changeUserStatus']);
    Route::get('users/getUserImages',[\App\Http\Controllers\UserController::class, 'getUserImages']);
    Route::get('users/investments', [\App\Http\Controllers\UserController::class, 'getInvestments'])->name('users.investments');

    Route::get('commissions',[\App\Http\Controllers\CommissionController::class, 'index'])->name('commissions');
    Route::get('commissions/changeCommissions',[\App\Http\Controllers\CommissionController::class, 'changeCommissions']);
    Route::get('commissions/getInvestmentsCommissions', [\App\Http\Controllers\CommissionController::class, 'getInvestmentsCommissions'])->name('commissions.getInvestmentsCommissions');

    Route::get('requests',[\App\Http\Controllers\RequestsController::class, 'index'])->name('requests');
    Route::get('requests/getCustomerRequests', [\App\Http\Controllers\RequestsController::class, 'getCustomerRequests'])->name('requests.getCustomerRequests');
    Route::post('requests/changeRequestStatus',[\App\Http\Controllers\RequestsController::class, 'changeRequestStatus']);
    Route::post('requests/replyRequests',[\App\Http\Controllers\RequestsController::class, 'replyRequests']);

    Route::get('gold',[GoldController::class, 'index'])->name('gold');
    Route::get('gold/getCustomerGold', [GoldController::class, 'getCustomerGold'])->name('gold.getCustomerGold');
    Route::post('gold/editWarehouseGold',[GoldController::class,'editWarehouseGold']);
    Route::get('gold/getUsers', [\App\Http\Controllers\GoldController::class, 'getUsers'])->name('gold.getUsers');

    Route::get('reports',[\App\Http\Controllers\ReportsController::class, 'index'])->name('reports');

    Route::get('chart-line', [\App\Http\Controllers\ChartController::class,'chartLine'])->name('');
    Route::get('chart-line-ajax',  [\App\Http\Controllers\ChartController::class,'chartLineAjax']);

    Route::get('reports-summary', [\App\Http\Controllers\ReportsController::class, 'reportSummary'])->name('reports.summary');
    Route::get('reports-users', [\App\Http\Controllers\ReportsController::class, 'reportUsers'])->name('reports.users');
    Route::get('reports-invests', [\App\Http\Controllers\ReportsController::class, 'reportInvests'])->name('reports.invests');
    Route::get('reports-requests', [\App\Http\Controllers\ReportsController::class, 'reportRequests'])->name('reports.requests');
    Route::get('reports-payments', [\App\Http\Controllers\ReportsController::class, 'reportPayments'])->name('reports.payments');


});


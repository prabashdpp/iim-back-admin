<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Models\CustomerRequests;
use App\Models\User;
use App\Models\WarehouseActions;
use App\Models\WarehouseDetails;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $dashboard_data['total_signups']= User::all()->count();

        $dashboard_data['gold_requests']= CustomerRequests::where('type','=',AppConstants::GOLD_OUT)->count();
        $dashboard_data['cash_requests']= CustomerRequests::where('type','=',AppConstants::CASH_OUT)->count();
        $dashboard_data['other_requests']= CustomerRequests::wherein('type',[AppConstants::GENERAL_REQUEST,AppConstants::CUSTOMER_QUESTION])->count();

        $dashboard_data['buy_total']= WarehouseActions::where('action','=',AppConstants::BUY)->sum('amount');
        $dashboard_data['sell_total']= WarehouseActions::where('action','=',AppConstants::SELL)->sum('amount');

        $warehouse=WarehouseDetails::where('item_id',AppConstants::MAIN_SELL_ITEM)->first();
        $dashboard_data['buy_commission']= $warehouse->buy_commission;
        $dashboard_data['sell_commission']= $warehouse->sell_commission;
        return view('dashboard',$dashboard_data);
    }
}

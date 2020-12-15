<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Models\CustomerRequests;
use App\Models\Payments;
use App\Models\User;
use App\Models\WarehouseActions;
use App\Models\WarehouseDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ReportsController extends Controller
{

    public function index()
    {
        $warehouse=WarehouseDetails::where('item_id',AppConstants::MAIN_SELL_ITEM)->first();
        $warehouse_data['warehouse_gold_amount']= $warehouse->gold_amount;
        $warehouse_data['warehouse_cash_amount']= $warehouse->cash_amount;
        $warehouse_data['updated_at']= $warehouse->updated_at;

        $users = User::select(\DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('count');
        return view('pages.reports', [ 'users' => $users ,'warehouse_data' => $warehouse_data] );
    }


    public function reportSummary()
    {
        $userData = User::select(\DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('count');


        $paymentData = Payments::select(\DB::raw("SUM(amount) as sum"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('sum');

        $requestData = CustomerRequests::select(\DB::raw("COUNT('*') as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("type"))
            ->pluck('count');

        $warehouseData = WarehouseActions::select(\DB::raw("COUNT('*') as count"),'action')
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("action"))
            ->pluck('count');


//        die($paymentData);

        return view('pages.reports.summary', [ 'userData' => $userData ,'paymentData' => $paymentData,'requestData' => $requestData,'warehouseData' => $warehouseData] );
    }

    public function reportUsers()
    {
        $userData = User::select(\DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('count');

        return view('pages.reports.users', compact('userData'));
    }

    public function reportInvests()
    {
        $userData = User::select(\DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('count');

        return view('pages.reports.invests', compact('userData'));
    }

    public function reportRequests()
    {
        $userData = User::select(\DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('count');

        return view('pages.reports.requests', compact('userData'));
    }

    public function reportPayments()
    {
        $userData = User::select(\DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('count');

        return view('pages.reports.payments', compact('userData'));
    }

}

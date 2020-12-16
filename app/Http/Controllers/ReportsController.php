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


        $requestData = CustomerRequests::select(\DB::raw("COUNT('*') as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("type"))
            ->pluck('count');
        $requestData[0]= ['Cash Out',$requestData[0]];
        $requestData[1]= ['Gold Out',$requestData[1]];
        $requestData[2]= ['General',$requestData[2]];
        $requestData[3]= ['Questions',$requestData[3]];


        $warehouseData = WarehouseActions::select('action',\DB::raw("COUNT('*') as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("action"))
            ->pluck('count');

        $warehouseData[0]= ['Buy',$warehouseData[0]];
        $warehouseData[1]= ['Sell',$warehouseData[1]];


        $paymentData = Payments::select(\DB::raw("SUM(amount) as sum"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('sum');

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

        return view('pages.reports.requests');
    }

    public function reportPayments()
    {
        $userData = User::select(\DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('count');

        return view('pages.reports.payments', compact('userData'));
    }

    public function getCustomerPayments(Request $request)
    {
        if ($request->ajax()) {

            $from_date = $request->from_date ? $request->from_date : '';
            $to_date = $request->to_date? $request->to_date : '';

            if($from_date){
                $from_date= date('Y-m-d', strtotime($from_date));
                $to_date= date('Y-m-d', strtotime($to_date));
                $data = Payments::join('users','users.id','=','payments.user_id')
                    ->whereRaw("(payments.created_at >= ? AND payments.created_at <= ?)",[$from_date." 00:00:00", $to_date." 23:59:59"])
                    ->select('payments.*','users.first_name','users.last_name')->get();
            }
            else{
                $data = Payments::join('users','users.id','=','payments.user_id')->select('payments.*','users.first_name','users.last_name')->get();
            }

            foreach ($data as $d){
                $d->created_at = date("d-m-Y", strtotime($d->created_at));

                $d->name = $d->first_name.' '.$d->last_name;

            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function getInvestments(Request $request)
    {
        if ($request->ajax()) {

            $from_date = $request->from_date ? $request->from_date : '';
            $to_date = $request->to_date? $request->to_date : '';

            if($from_date){
                $from_date= date('Y-m-d', strtotime($from_date));
                $to_date= date('Y-m-d', strtotime($to_date));
                $data = WarehouseActions::join('users','users.id','=','warehouse_actions.user_id')
                    ->whereRaw("(warehouse_actions.created_at >= ? AND warehouse_actions.created_at <= ?)",[$from_date." 00:00:00", $to_date." 23:59:59"])
                    ->select('warehouse_actions.*','users.first_name','users.last_name')->get();
            }
            else{
                $data = WarehouseActions::join('users','users.id','=','warehouse_actions.user_id')->select('warehouse_actions.*','users.first_name','users.last_name')->get();
            }

            foreach ($data as $d){
                $d->created_at = date("d-m-Y", strtotime($d->created_at));

                if($d->action==AppConstants::BUY){
                    $d->action='Buy';
                    $d['total_commission'] = number_format(($d->trade_value * $d->commission * $d->amount)/(100 + $d->commission),4);
                }
                else{
                    $d->action='Sell';
                    $d['total_commission'] = number_format( ($d->trade_value *  $d->commission * $d->amount) / (100 - $d->commission),4);
                }

                $d->name = $d->first_name.' '.$d->last_name;

            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

}

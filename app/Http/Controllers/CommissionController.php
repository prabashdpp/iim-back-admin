<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\WarehouseActions;
use App\Models\WarehouseDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class CommissionController extends Controller
{

    public function index()
    {
        $warehouse=WarehouseDetails::where('item_id',AppConstants::MAIN_SELL_ITEM)->first();
        $commission_data['buy_commission']= $warehouse->buy_commission;
        $commission_data['sell_commission']= $warehouse->sell_commission;
        $commission_data['updated_at']= $warehouse->updated_at;
        return view('pages.commissions',$commission_data);
    }


    public function changeCommissions(Request $request)
    {
        $warehouse = WarehouseDetails::where('item_id','GOLD24')->first();
        $warehouse->buy_commission = $request->buy_commission;
        $warehouse->sell_commission = $request->sell_commission;
        $warehouse->save();
        return response()->json(['success'=>'Commission Details Updated!']);
    }



    public function getInvestmentsCommissions(Request $request)
    {
        if ($request->ajax()) {

            $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
            $to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');

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
               // $d->created_at = date("d-m-Y", strtotime($d->created_at));

                if($d->action==AppConstants::BUY){
                    $d->action='Buy';
                    $d['total'] = number_format(($d->trade_value * $d->commission)/(100 + $d->commission),4);
                }
                else{
                    $d->action='Sell';
                    $d['total'] = number_format( ($d->trade_value *  $d->commission) / (100 - $d->commission),4);
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}

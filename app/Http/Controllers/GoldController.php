<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Models\User;
use App\Models\WarehouseActions;
use App\Models\WarehouseDetails;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class GoldController extends Controller
{

    public function index()
    {
        $warehouse=WarehouseDetails::where('item_id',AppConstants::MAIN_SELL_ITEM)->first();
        $warehouse_data['warehouse_gold_amount']= $warehouse->gold_amount;
        $warehouse_data['warehouse_cash_amount']= $warehouse->cash_amount;
        $warehouse_data['updated_at']= $warehouse->updated_at;
        return view('pages.gold',$warehouse_data);
    }


    public function editWarehouseGold(Request $request)
    {
        $warehouse = WarehouseDetails::where('item_id',AppConstants::MAIN_SELL_ITEM)->first();

        if(is_numeric($request->add_gold_amount)){
            $warehouse->gold_amount= $warehouse->gold_amount + $request->add_gold_amount;
        }
        if(is_numeric($request->add_cash_amount)){
            $warehouse->cash_amount = ($warehouse->cash_amount + $request->add_cash_amount);
        }
        $warehouse->save();
        $warehouse_data['warehouse_gold_amount']= $warehouse->gold_amount;
        $warehouse_data['warehouse_cash_amount']= $warehouse->cash_amount;
        return response()->json(['success'=>'Warehouse Details Updated!','warehouse_data'=>$warehouse_data]);
    }



    public function getCustomerGold(Request $request)
    {
        if ($request->ajax()) {

            $from_date =  $request->from_date  ? date('Y-m-d', strtotime($request->from_date)) : '';
            $to_date =   $request->to_date    ? date('Y-m-d', strtotime($request->to_date)) :  '';
            $request_type = $request->filter_request_type ? $request->filter_request_type  : '';
            $today = date('Y-m-d');
            $early_date = date('Y-m-d',PHP_INT_MIN);


            if($from_date || $to_date || $request_type){

                $data = WarehouseActions::join('users','users.id','=','warehouse_actions.user_id')
                    ->where(function ($query) use ($request_type) {
                        if ($request_type != -1) {
                            $query->where("warehouse_actions.action", '=', $request_type);
                        }
                    })->where(function ($query) use ($from_date,$to_date,$early_date,$today) {
                        if ($from_date && $to_date) {
                            $query->whereRaw("(warehouse_actions.created_at >= ? AND warehouse_actions.created_at <= ?)",[$from_date." 00:00:00", $to_date." 23:59:59"]);
                        }
                        elseif($from_date){
                            $query->whereRaw("(warehouse_actions.created_at >= ? AND warehouse_actions.created_at <= ?)",[$from_date." 00:00:00", $today." 23:59:59"]);
                        }
                        elseif($to_date){
                            $query->whereRaw("(warehouse_actions.created_at >= ? AND warehouse_actions.created_at <= ?)",[$from_date." 00:00:00", $to_date." 23:59:59"]);
                        }
                        else{
                            $query->whereRaw("(warehouse_actions.created_at >= ? AND warehouse_actions.created_at <= ?)",[$early_date." 00:00:00", $today." 23:59:59"]);
                        }
                    })
                    ->select('warehouse_actions.*', 'users.first_name', 'users.last_name', 'users.email', 'users.mobile')->get();
            }
            else{
                $data = WarehouseActions::join('users','users.id','=','warehouse_actions.user_id')->select('warehouse_actions.*','users.first_name','users.last_name')->get();
            }

            foreach ($data as $d){
                $d->created_at = date("d-m-Y", strtotime($d->created_at));

                if($d->action==AppConstants::BUY){
                    $d->action='Buy';
                    $d['total'] = number_format(($d->trade_value * $d->commission * $d->amount)/(100 + $d->commission),4);
                }
                else{
                    $d->action='Sell';
                    $d['total'] = number_format( ($d->trade_value *  $d->commission * $d->amount) / (100 - $d->commission),4);
                }

                $d->name = $d->first_name.' '.$d->last_name;

            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('options', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="view_user btn btn-info btn-sm" data-id='.$row->user_id.'>View User</a>';
                    return $actionBtn;
                })
                ->rawColumns(['options'])
                ->make(true);
        }
    }


    public function getUsers(Request $request)
    {
        if ($request->ajax()) {

            $data = User::where('id','=',$request->user_id)->get();


            // $data = User::find($request->user_id);
           // print_r($data);

            foreach ($data as $d){
                if($d->gender==AppConstants::GENDER_MALE){
                    $d->gender='Male';
                }
                elseif ($d->gender==AppConstants::GENDER_FEMALE){
                    $d->gender='Female';
                }
                else{
                    $d->gender='Other';
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}

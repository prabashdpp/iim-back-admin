<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Models\CustomerRequests;
use App\Models\Notifications;
use App\Models\User;
use App\Models\WarehouseActions;
use App\Models\WarehouseDetails;
use Illuminate\Http\Request;

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

        $userData = User::select(\DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('count');

        $dashboard_data['userData']=$userData;


        $requestData = CustomerRequests::select(\DB::raw("COUNT('*') as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("type"))
            ->pluck('count');
        $requestData[0]= ['Cash Out',$requestData[0]];
        $requestData[1]= ['Gold Out',$requestData[1]];
        $requestData[2]= ['General',$requestData[2]];
        $requestData[3]= ['Questions',$requestData[3]];
        $dashboard_data['requestData']=$requestData;


        $warehouseData = WarehouseActions::select('action',\DB::raw("COUNT('*') as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("action"))
            ->pluck('count');

        $warehouseData[0]= ['Buy',$warehouseData[0]];
        $warehouseData[1]= ['Sell',$warehouseData[1]];
        $dashboard_data['warehouseData']=$warehouseData;


        return view('dashboard',$dashboard_data);
    }

    public function markNotificationAsRead(Request $request)
    {
        auth()->user()
            ->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        $unread_notification_count = Notifications::where(['notifiable_id'=> auth()->user()->id,'read_at' => null])->count();

        return response()->json(['notification_count'=>$unread_notification_count]);
    }

    public function getUnreadNotifications(): string
    {
        $html='';

        if(auth()->user()->is_admin){
            foreach(auth()->user()->unreadNotifications as $notification) {
                $html='<div class="dropdown-item" role="alert" style="width:700px">'.$notification->created_at.' User '.$notification->data['message'];

                $html .='<div style="width:250px;max-width:500px; display: inline-block">
                                    <a href="#" class="dropdown-item float-right " > </a></div>
                                <div style="width: 100px"><button class="btn-info pull-left mark-as-read" data-id="{{ $notification->id }}">Mark as read</button></div>
                            </div>';

                if($notification->last){
                    $html .='<a href="#" id="mark-all">
                                    <P class="dropdown-item text-danger">Mark all as read</P>
                                </a>';
                }
                else{
                    $html .='There are no new notifications';
                }
            }
            return $html;
        }

    }

}

<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Models\CustomerRequests;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RequestsController extends Controller
{

    public function index()
    {
        $requests_data['gold_requests']= CustomerRequests::where('type','=',AppConstants::GOLD_OUT)->count();
        $requests_data['cash_requests']= CustomerRequests::where('type','=',AppConstants::CASH_OUT)->count();
        $requests_data['other_requests']= CustomerRequests::wherein('type',[AppConstants::GENERAL_REQUEST,AppConstants::CUSTOMER_QUESTION])->count();
        $requests_data['last_updated']= CustomerRequests::latest()->first()->created_at;

        return view('pages.requests',$requests_data);
    }


    public function getCustomerRequests(Request $request)
    {
        if ($request->ajax()) {

            $from_date = $request->from_date ? $request->from_date : '';
            $to_date = $request->to_date ? $request->to_date  : '';

            if($from_date){
                $from_date= date('Y-m-d', strtotime($from_date));
                $to_date= date('Y-m-d', strtotime($to_date));
                $data = CustomerRequests::join('users','users.id','=','customer_requests.user_id')
                    ->whereRaw("(customer_requests.created_at >= ? AND customer_requests.created_at <= ?)",[$from_date." 00:00:00", $to_date." 23:59:59"])
                    ->select('customer_requests.*','users.first_name','users.last_name','users.email','users.mobile')->get();
            }
            else{
                $data = CustomerRequests::join('users','users.id','=','customer_requests.user_id')->select('customer_requests.*','users.first_name','users.last_name','users.email','users.mobile')->get();

            }

            foreach ($data as $d){
                $d->created_at = date("d-m-Y", strtotime($d->created_at));

                if($d->type==AppConstants::CASH_OUT){
                    $d->request_type='Cash Out';
                }
                else if($d->type==AppConstants::GOLD_OUT){
                    $d->request_type='Gold Out';
                }
                else{
                    $d->request_type='Other';
                }

                $d->name = $d->first_name.' '.$d->last_name;

            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('options', function($row){
                  //  $actionBtn = '<a href="javascript:void(0)" data-id='.$row->id.'  class="viewimages btn btn-warning btn-sm">View</a>';
                    if($row->request_status==AppConstants::COMPLETE){
                        $actionBtn = '<input data-id='.$row->id.' class="toggle-class" id="toggle-class"  type="checkbox"  data-toggle="toggle" data-height="1" data-width="150" data-on="Complete" data-off="Pending" data-onstyle="success" data-offstyle="warning" checked>' ;
                    }
                    else{
                        $actionBtn ='<input data-id='.$row->id.' class="toggle-class" id="toggle-class"  type="checkbox"  data-toggle="toggle" data-height="1" data-width="150" data-on="Complete" data-off="Pending" data-onstyle="success" data-offstyle="warning">' ;
                    }
                    if(in_array($row->type,array(AppConstants::GENERAL_REQUEST,AppConstants::CUSTOMER_QUESTION))){
                        $actionBtn .= '<a href="javascript:void(0)" class="request_reply btn btn-info btn-sm" data-id='.$row->id.'>Reply</a>';
                    }
                    else{
                        $actionBtn .= '<a href="javascript:void(0)" class="request_action btn btn-info btn-sm" data-id='.$row->id.'>Reply</a>';
                    }

                    return $actionBtn;
                })
                ->rawColumns(['options'])
                ->make(true);

        }
    }


    public function changeRequestStatus(Request $request)
    {
        $customerRequest = CustomerRequests::find($request->request_id);
        $customerRequest->request_status = $request->status;
        $customerRequest->save();

        return response()->json(['success'=>'Request Status change successfully.']);
    }

    public function replyRequests(Request $request)
    {
        $customerRequest = CustomerRequests::find($request->requestId);

        if($customerRequest->type==AppConstants::CASH_OUT){
            if($request->acceptRequest==AppConstants::REQUEST_RESULT_ACCEPTED){
                $message= 'Cash Out Request Approved.';
            }
            else{
                $message= 'Cash Out Request Rejected.';
            }
            $customerRequest->result_status=$request->acceptRequest;
        }


        elseif ($customerRequest->type==AppConstants::GOLD_OUT){
            $message= 'Cash Out Request Approved.';

        }
        elseif ($customerRequest->type==AppConstants::GENERAL_REQUEST){
            $message= 'Cash Out Request Approved.';

        }
        elseif ($customerRequest->type==AppConstants::CUSTOMER_QUESTION){
            $message= 'Cash Out Request Approved.';

        }

        return response()->json(['success'=>$message]);
    }
}

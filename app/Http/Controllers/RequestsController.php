<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Models\CustomerRequests;
use App\Models\CustomerRequestsDetails;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\AppNotification;
use Yajra\DataTables\DataTables;
use Notification;

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

            $from_date =  $request->from_date  ? date('Y-m-d', strtotime($request->from_date)) : '';
            $to_date =   $request->to_date    ? date('Y-m-d', strtotime($request->to_date)) :  '';
            $request_type = $request->filter_request_type ? $request->filter_request_type  : '';
            $today = date('Y-m-d');
            $early_date = date('Y-m-d',PHP_INT_MIN);



            if($from_date || $to_date || $request_type){

                $data = CustomerRequests::join('users','users.id','=','customer_requests.user_id')
                ->where(function ($query) use ($request_type) {
                    if ($request_type != -1) {
                        $query->where("customer_requests.type", '=', $request_type);
                    }
                })->where(function ($query) use ($from_date,$to_date,$early_date,$today) {
                        if ($from_date && $to_date) {
                            $query->whereRaw("(customer_requests.created_at >= ? AND customer_requests.created_at <= ?)",[$from_date." 00:00:00", $to_date." 23:59:59"]);
                        }
                        elseif($from_date){
                            $query->whereRaw("(customer_requests.created_at >= ? AND customer_requests.created_at <= ?)",[$from_date." 00:00:00", $today." 23:59:59"]);
                        }
                        elseif($to_date){
                            $query->whereRaw("(customer_requests.created_at >= ? AND customer_requests.created_at <= ?)",[$from_date." 00:00:00", $to_date." 23:59:59"]);
                        }
                        else{
                            $query->whereRaw("(customer_requests.created_at >= ? AND customer_requests.created_at <= ?)",[$early_date." 00:00:00", $today." 23:59:59"]);
                        }
                    })
                ->select('customer_requests.*', 'users.first_name', 'users.last_name', 'users.email', 'users.mobile')->get();
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
                else if($d->type==AppConstants::GENERAL_REQUEST){
                    $d->request_type='General';
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






    public function getPreviousActivities(Request $request)
    {
        if ($request->ajax()) {

            $data = CustomerRequestsDetails::join('users','users.id','=','customer_requests_details.user_id')
                    ->where('request_id','=',$request->request_id)
                    ->select('customer_requests_details.*','users.first_name','users.last_name')->get();

            foreach ($data as $d){
                $d->name = $d->first_name.' '.$d->last_name;
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }


    public function replyRequests(Request $request)
    {
        $requestId=$request->requestId ? $request->requestId : '';
        $customerRequest = CustomerRequests::find($requestId);
        $responseMessage = $request->responseMessage ? $request->responseMessage : '';
        $user = User::find($customerRequest->user_id);
        $userId = $user->id;
        $resultStatus = $request->acceptRequest ? $request->acceptRequest : 0;


        if($customerRequest->type==AppConstants::CASH_OUT){
            $subject = 'Feedback for Cash Out Request';
            if($request->acceptRequest==AppConstants::REQUEST_RESULT_ACCEPTED){
                $message= 'approved Cash Out Request of' . ' '. $customerRequest->amount . ' ' .$user->currency.'.';
                $body='We have approved your Cash Out request of ' . $customerRequest->amount . ' ' . $user->currency.'
                       Our team has already started to process your request and you will get the Cash amount to the Bank Account you have provided. '
                       .$responseMessage;
                $description = $message.' '.$responseMessage;

            }
            else{
                $message= 'rejected Cash Out Request of' . ' '. $customerRequest->amount . ' ' .$user->currency.'.';
                $body='We have rejected your Cash Out request of ' . $customerRequest->amount . ' ' . $user->currency.'
                       Our team has already started to process your request and you will get the Cash amount to the Bank Account you have provided. '
                    .$responseMessage;
                $description = $message.' '.$responseMessage;
            }
        }

        elseif ($customerRequest->type==AppConstants::GOLD_OUT){
            $subject = 'Feedback for Gold Out Request';
            if($request->acceptRequest==AppConstants::REQUEST_RESULT_ACCEPTED){
                $message= 'approved Gold Out Request of' . ' '. $customerRequest->amount . ' ' .AppConstants::INSTRUMENT_UNIT.'.';
                $body='We have approved your Cash Out request of ' . $customerRequest->amount . ' ' . AppConstants::INSTRUMENT_UNIT.'
                       Our team has already started to process your request and you will get the Cash amount to the Bank Account you have provided. '
                    .$responseMessage;
                $description = $message.' '.$responseMessage;

            }
            else{
                $message= 'rejected Gold Out Request of' . ' '. $customerRequest->amount . ' ' .AppConstants::INSTRUMENT_UNIT.'.';
                $body='We have rejected your Cash Out request of ' . $customerRequest->amount . ' ' . AppConstants::INSTRUMENT_UNIT.'
                       Our team has already started to process your request and you will get the Cash amount to the Bank Account you have provided. '
                    .$responseMessage;
                $description = $message.' '.$responseMessage;
            }

        }
        elseif ($customerRequest->type==AppConstants::GENERAL_REQUEST){
            $subject = 'Feedback for General Request';
            if($request->acceptRequest==AppConstants::REQUEST_RESULT_RESOLVED){
                $message= 'marked request as resolved';
                $body= 'We have marked your requests as Resolved '.$responseMessage;
                $description = $message.' '.$responseMessage;

            }
            else{
                $message= 'General Request has replied';
                $body=$responseMessage;
                $description = $message.' '.$responseMessage;
            }

        }
        elseif ($customerRequest->type==AppConstants::CUSTOMER_QUESTION){
            $subject = 'Feedback for Question';
            if($request->acceptRequest==AppConstants::REQUEST_RESULT_RESOLVED){
                $message= 'marked request as resolved';
                $body='We have marked your Issue as Resolved '.$responseMessage;
                $description = $message.' '.$responseMessage;

            }
            else{
                $message= 'Question Request has replied';
                $body=$responseMessage;
                $description = $message.' '.$responseMessage;
            }
        }


        //common saving process for all requests and send notifications
        $customerRequest->result_status=$resultStatus;
        $customerRequest->save();

        $customer_requests_details= [
            'request_id' => $requestId,
            'description' => $description,
            'message' => $message,
            'user_id' => $userId,
            'result_status' => $resultStatus
        ];

        CustomerRequestsDetails::Create($customer_requests_details);

        $details = [
            'subject' => $subject,
            'email' => TRUE,
            'greeting' => 'Hi ' . $user->first_name,
            'body' => $body,
            'thanks' => 'Thank you for using Invest in Moi',
            'message' => $message
        ];

        Notification::send($user, new AppNotification($details));

        return response()->json(['success'=>$message]);
    }
}

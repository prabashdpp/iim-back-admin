<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\WarehouseActions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        return view('users.index', ['users' => $model->paginate(15)]);
    }

    public function getUsers(Request $request)
    {
        if ($request->ajax()) {

            $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
            $to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');

            if($from_date){
                $from_date= date('Y-m-d', strtotime($from_date));
                $to_date= date('Y-m-d', strtotime($to_date));
                $data = User::whereRaw("(created_at >= ? AND created_at <= ?)",[$from_date." 00:00:00", $to_date." 23:59:59"])->get();
            }
            else{
                $data = User::get();
            }
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
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" data-id='.$row->id.'  class="viewimages btn btn-warning btn-sm">View</a>';
                    if($row->user_status==1){
                        $actionBtn .= '<input data-id='.$row->id.' class="toggle-class" id="toggle-class"  type="checkbox"  data-toggle="toggle" data-height="1" data-width="150" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger" checked>' ;
                    }
                    else{
                       $actionBtn .='<input data-id='.$row->id.' class="toggle-class" id="toggle-class"  type="checkbox"  data-toggle="toggle" data-height="1" data-width="150" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger">' ;
                    }
                    $actionBtn .= '<a href="javascript:void(0)" class="investor_history btn btn-default btn-sm" data-id='.$row->id.'>History</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function changeUserStatus(Request $request)
    {
        $user = User::find($request->user_id);
        $user->user_status = $request->status;
        $user->save();

        return response()->json(['success'=>'Status change successfully.']);
    }

    public function getInvestments(Request $request)
    {
        if ($request->ajax()) {

            $user_id = (!empty($_GET["user_id"])) ? ($_GET["user_id"]) : ('');

                $data = WarehouseActions::latest()->where('user_id','=',$user_id)->get();

                foreach ($data as $d){
                    $d->created_at= date('d-m-Y', strtotime($d->created_at));

                        if($d->action==AppConstants::BUY){
                            $d->action='Buy';
                        }
                        else{
                            $d->action='Sell';
                        }
                    }


            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }


    public function getUserImages(Request $request)
    {
        if ($request->ajax()) {
            $user = User::find($request->user_id);
            $data['profile_image']=$user->profile_image;
            $data['images']=$user->images;
            $html ='<div>';
            foreach ($user->images as $a => $b){
                $html .='<div class="images"><img src ="'.$b['image_url'].'"></div>';
            }
            $html ='</div>';

            $data['html']=$html;

            return response()->json(['success'=>$request->user_id,'images'=>$data]);
        }
    }
}

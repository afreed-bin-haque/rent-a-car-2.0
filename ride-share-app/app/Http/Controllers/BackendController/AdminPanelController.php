<?php

namespace App\Http\Controllers\BackendController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class AdminPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function VehicleApprovalPanel(Request $request){
        $serach_vehicle =$request->query('car_query');
        $user_status = Helpers::FetchUserRole();
        if (!empty($serach_vehicle)) {
            $fetch_cars_approval_panel = DB::table('vehicle_details')
            ->join('rider_details','vehicle_details.author','rider_details.email')
            ->where('vehicle_details.model','Like', "%$serach_vehicle%")
            ->select('vehicle_details.vehicle_id','vehicle_details.mileage','vehicle_details.plate','vehicle_details.condition',
            'vehicle_details.model','vehicle_details.type','vehicle_details.author',
            'rider_details.contact','rider_details.address','vehicle_details.status','vehicle_details.main_image',
            DB::raw('DATE(vehicle_details.created_at) AS registered_at'),DB::raw('DATE(vehicle_details.updated_at) AS approved_at'))
            ->orderBy('vehicle_details.vehicle_id', 'DESC')
            ->paginate('4');
        }else{
            $fetch_cars_approval_panel = DB::table('vehicle_details')
            ->join('rider_details','vehicle_details.author','rider_details.email')
            ->select('vehicle_details.vehicle_id','vehicle_details.mileage','vehicle_details.plate','vehicle_details.condition',
            'vehicle_details.model','vehicle_details.type','vehicle_details.author',
            'rider_details.contact','rider_details.address','vehicle_details.status','vehicle_details.main_image',
            DB::raw('DATE(vehicle_details.created_at) AS registered_at'),DB::raw('DATE(vehicle_details.updated_at) AS approved_at'))
            ->orderBy('vehicle_details.vehicle_id', 'DESC')
            ->paginate('4');
        }
        return view('pages.backend.dashboard.helping-page.admin.vehicle-approval-panel',compact('user_status','fetch_cars_approval_panel'));
    }

    public function ViewVehicleDetails(Request $request){
        $user_status = Helpers::FetchUserRole();
        $vehicle_id = $request->vehicle_id;
        $fetch_cars_details = DB::table('vehicle_details')
            ->join('rider_details','vehicle_details.author','rider_details.email')
            ->where('vehicle_details.vehicle_id','=', $vehicle_id)
            ->select('vehicle_details.vehicle_id','vehicle_details.mileage','vehicle_details.plate','vehicle_details.condition',
            'vehicle_details.model','vehicle_details.seats','vehicle_details.color','vehicle_details.type','vehicle_details.author',
            'rider_details.contact','rider_details.address','vehicle_details.status','vehicle_details.main_image',
            DB::raw('DATE(vehicle_details.created_at) AS registered_at'),DB::raw('DATE(vehicle_details.updated_at) AS approved_at'))
            ->get();
        $multi_images = DB::table('vehicle_multi_images')
         ->where('vehicle_id','=', $vehicle_id)
          ->select('img_description','img_path')
          ->get();
         return view('pages.backend.dashboard.helping-page.admin.vehicle-view',compact('user_status','fetch_cars_details','multi_images'));
    }

    public function VehicleSatusChange(Request $request){
         $vehicle_id = $request->vehicle_id;
         $current_status = $request->current_status;
         if($current_status === 'Inactive'){
            $new_status = 'Active';
         }elseif($current_status === 'Active'){
            $new_status = 'Inactive';
         }
         $update_data =[
            'status' => $new_status,
            'updated_at' =>  Carbon::now()
         ];
         DB::table('vehicle_details')
          ->where('vehicle_id','=', $vehicle_id)
          ->update($update_data);
          $fetch_current_data = DB::table('vehicle_details')
            ->where('vehicle_id','=', $vehicle_id)
            ->select('mileage','plate','model','type','author','status',
            DB::raw('DATE(created_at) AS registered_at'),DB::raw('DATE(updated_at) AS approved_at'))
            ->get();
            foreach($fetch_current_data as $current_data){
                $author = $current_data->author;
                $plate = $current_data->plate;
                $model = $current_data->model;
                $type = $current_data->type;
                $status = $current_data->status;
                $registered_at = $current_data->registered_at;
                $approved_at = $current_data->approved_at;
            }
             $data=['author'=> $author,
            'car_model' => $model,
            'plate' => $plate,
            'model' => $model,
            'status' => $status,
            'registered_at' => $registered_at,
            'approved_at' => $approved_at
            ];
              $user['to']= $author;
                Mail::send('emails.vehicle-approval-mail',$data,function($messages)use($user){
                    $messages->to($user['to']);
                    $messages->subject('Approval for vehicle registration');
                });
           return redirect()->route('vehicle.approvalPanel')->with('success', 'Vehicle Approved Successfully');
    }

    public function ViewTravelPostApprovelPanel(Request $request){
           $serach_author =$request->query('author_query');
            $user_status = Helpers::FetchUserRole();
            if(!empty($serach_author)){
             $fetch_post = DB::table('posts')
                ->join('vehicle_details','posts.vehicle_id','vehicle_details.vehicle_id')
                ->join('rider_details','posts.author','rider_details.email')
                ->orWhere('vehicle_details.model','Like', "%$serach_author%")
                ->orWhere('posts.jurney_date','Like', "%$serach_author%")
                ->orWhere('posts.author','Like', "%$serach_author%")
                ->orWhere('posts.status','Like', "%$serach_author%")
                ->select('posts.*','vehicle_details.main_image','vehicle_details.model','rider_details.name','rider_details.email','rider_details.contact',
                DB::raw('DATE(posts.created_at) AS registered_at'),DB::raw('DATE(posts.updated_at) AS approved_at'))
                ->paginate(10);
            }else{
            $fetch_post = DB::table('posts')
            ->join('vehicle_details','posts.vehicle_id','vehicle_details.vehicle_id')
            ->join('rider_details','posts.author','rider_details.email')
            ->select('posts.*','vehicle_details.main_image','vehicle_details.model','rider_details.name','rider_details.email','rider_details.contact',
             DB::raw('DATE(posts.created_at) AS registered_at'),DB::raw('DATE(posts.updated_at) AS approved_at'))
            ->paginate(10);
            }
            return view('pages.backend.dashboard.helping-page.admin.post-approval-panel',compact('user_status','fetch_post'));
    }

    public function ChangePostStatus(Request $request){
         $post_id = $request->post_id;
         $current_status = $request->current_status;
         if($current_status === 'Pending'){
            $new_status = 'Approved';
         }elseif($current_status === 'Approved'){
            $new_status = 'Pending';
         }
         $update_data =[
            'status' => $new_status,
            'updated_at' => Carbon::now()
         ];
         DB::table('posts')
          ->where('post_id','=', $post_id)
          ->update($update_data);
          $fetch_current_data = DB::table('posts')
            ->where('post_id','=', $post_id)
            ->select('plate','jurney_date','from_loc','to_loc','seat','price_per_seat','total_fare','author','status',
            DB::raw('DATE(created_at) AS registered_at'),DB::raw('DATE(updated_at) AS approved_at'))
            ->get();
            foreach($fetch_current_data as $current_data){
                $author = $current_data->author;
                $plate = $current_data->plate;
                $jurney_date = $current_data->jurney_date;
                $from_loc = $current_data->from_loc;
                $to_loc = $current_data->to_loc;
                $seat = $current_data->seat;
                $price_per_seat = $current_data->price_per_seat;
                $total_fare= $current_data->total_fare;
                $status = $current_data->status;
                $registered_at = $current_data->registered_at;
                $approved_at = $current_data->approved_at;
            }
            $request_date =  date('d-m-Y');
             $data=[
            'author'=> $author,
            'plate' => $plate,
            'jurney_date' => $jurney_date,
            'from_city' => $from_loc,
            'to_city' => $to_loc,
            'total_seat' => $seat,
            'single_seat_fare' => $price_per_seat,
            'total_fare' =>$total_fare,
            'status' =>$status,
            'registered_at' => $registered_at,
            'approved_at' => $approved_at,
            'date' => $request_date
            ];
            $user['to']=$author;
            Mail::send('emails.approval-post-mail',$data,function($messages)use($user){
                $messages->to($user['to']);
                $messages->subject('Approval for Journey Post');
            });
         return redirect()->route('post.approvalPanel')->with('success', 'Post Status Changed Successfully');
    }

    public function DeletePost(Request $request){
          $post_id = $request->post_id;
          DB::table('posts')
           ->where('post_id','=', $post_id)
           ->delete();
           return redirect()->route('post.approvalPanel')->with('success', 'Post Deleted Successfully');
    }
}

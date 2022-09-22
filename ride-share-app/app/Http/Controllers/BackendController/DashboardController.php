<?php

namespace App\Http\Controllers\BackendController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function DashboardRouting(Request $request){
        $fetch_user_details = DB::table('app_user_roles')
            ->where('email', '=', Auth::user()->email)
            ->get();
        foreach ($fetch_user_details as $user_details) {
            $user_status = $user_details->role;
        }
        if ($user_status === 'User'){
            $from_city = $request->from_city;
            $to_city = $request->to_city;
            $vehicle_type = $request->vehicle_type;
            $fetch_city = DB::table('cities_tables')
                ->get();
            $fetch_vehicle_type = DB::table('vehicle_types')
                ->get();

            $latest_date = Date('Y-m-d', strtotime('+6 days'));
            $today = Date('Y-m-d');
                if($vehicle_type === 'all'){
                            $fetch_trip = DB::table('posts')
                            ->join('vehicle_details','posts.vehicle_id','vehicle_details.vehicle_id')
                            ->join('rider_details','posts.author','rider_details.email')
                            ->where('posts.from_loc','=', $from_city)
                            ->where('posts.to_loc','=', $to_city)
                            ->where('posts.status','=','Approved')
                            ->where('posts.seat','>','0')
                            ->where('posts.jurney_date' ,'>=', $today)
                            ->where('posts.jurney_date' ,'<=', $latest_date)
                            ->select('posts.*','vehicle_details.model','vehicle_details.main_image','vehicle_details.condition','rider_details.rating')
                            ->paginate(10);
                }else{
                            $fetch_trip = DB::table('posts')
                            ->join('vehicle_details','posts.vehicle_id','vehicle_details.vehicle_id')
                            ->join('rider_details','posts.author','rider_details.email')
                            ->where('posts.from_loc','=', $from_city)
                            ->where('posts.to_loc','=', $to_city)
                            ->where('vehicle_details.type','=', $vehicle_type)
                            ->where('posts.status','=','Approved')
                            ->where('seat','>','0')
                            ->where('posts.jurney_date' ,'>=', $today)
                            ->where('posts.jurney_date' ,'<', $latest_date)
                            ->select('posts.*','vehicle_details.model','vehicle_details.main_image','vehicle_details.condition','rider_details.rating')
                            ->paginate(10);
                }
                 $booking_details = DB::table('bookings')
                            ->join('posts','bookings.post_id','posts.post_id')
                            ->where('bookings.booked_by','=',Auth::user()->email)
                             ->where('bookings.status','!=','Paid')
                            ->select('posts.from_loc','posts.to_loc','posts.plate','posts.jurney_date','bookings.*')
                            ->get();
            return view('pages.backend.dashboard.user', compact('user_status', 'fetch_city', 'fetch_vehicle_type','fetch_trip','booking_details'));
        }elseif($user_status === 'Rider'){
             $latest_date = Date('Y-m-d', strtotime('+6 days'));
            $today = Date('Y-m-d');
            $fetch_city = DB::table('cities_tables')
            ->get();

            $fetch_post = DB::table('posts')
            ->join('vehicle_details','posts.vehicle_id','vehicle_details.vehicle_id')
             ->where('posts.author', '=', Auth::user()->email)
            ->select('posts.*','vehicle_details.main_image','vehicle_details.model')
            ->paginate(10);

            $vehicle_details = DB::table('vehicle_details')
                ->where('author', '=', Auth::user()->email)
                ->where('status', '=', 'Active')
            ->get();
            $travel_details =  DB::table('bookings')
                            ->join('posts','bookings.post_id','posts.post_id')
                            ->join('trackings','bookings.booking_id','trackings.booking_id')
                            ->join('user_details','bookings.booked_by','user_details.email')
                            ->where('posts.author', '=', Auth::user()->email)
                             ->where('posts.jurney_date' ,'>=', $today)
                            ->where('posts.jurney_date' ,'<=', $latest_date)
                            ->select('bookings.booking_id','bookings.seat_booked','bookings.total_fare_cost','posts.*','trackings.status','user_details.name','user_details.contact')
                            ->paginate(10);
            return view('pages.backend.dashboard.rider', compact('user_status', 'fetch_city', 'vehicle_details','fetch_post','travel_details'));
        }elseif($user_status === 'Admin'){
            return view('pages.backend.dashboard.admin', compact('user_status'));
        }
    }

    /**Fetch rider details*/
    public function fetchRiderDetails(Request $request){
        $author = $request->author;
        $verufy_rider_existance = DB::table('trackings')
                           ->where([
                                ['status','!=','Pending'],
                                ['status','!=','Complete'],
                                ['booked_by','=',Auth::user()->email]
                            ])
                            ->count();
        if($verufy_rider_existance === 1){
            $rider_traking_id = DB::table('trackings')
                           ->where([
                                ['status','!=','Pending'],
                                ['status','!=','Complete'],
                                ['booked_by','=',Auth::user()->email]
                            ])
                            ->get();
                            foreach($rider_traking_id as $rider_details){
                                $rider_mail = $rider_details->rider_email;
                            }
                            $rider_detail = DB::table('rider_details')
                            ->where('email','=',$rider_mail)
                            ->get();
            return response()->json([
                'rider_detail_existance'=>'yes',
                'rider_detail'=>$rider_detail
            ],200);
        }else{
             return response()->json([
                'rider_detail_existance'=>'no'
            ],200);
        }
    }
}

<?php

namespace App\Http\Controllers\BackendController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Models\Booking;
use App\Models\Tracking;
use App\Models\Ratting;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

    public function ViewbookingPostDetails(Request $request){
        $post_id = $request->post_id;
        $vehicle_id = $request->vehicle_id;
        $today = Date('Y-m-d');
        $latest_date = Date('Y-m-d', strtotime('+6 days'));
        $user_status = Helpers::FetchUserRole();
        $verify_post_data = DB::table('posts')
        ->where([
            ['post_id','=',$post_id],
            ['jurney_date','>=', $today],
            ['jurney_date' ,'<', $latest_date],
            ['seat','>','0'],
            ['status','=','Approved']
        ])
        ->count();
        if($verify_post_data === 1){
            $post_details = DB::table('posts')
            ->where([
                ['post_id','=',$post_id],
                ['jurney_date','>=', $today],
                ['jurney_date' ,'<', $latest_date],
                ['seat','>','0'],
                ['status','=','Approved']
            ])
            ->get();
            $vehicle_details = DB::table('vehicle_details')
            ->join('rider_details','vehicle_details.author','rider_details.email')
            ->where([
                ['vehicle_details.vehicle_id','=',$vehicle_id],
                ['vehicle_details.status','=','Active']
            ])
            ->select('vehicle_details.*','rider_details.rating')
            ->get();
             $vehicle_multi_image = DB::table('vehicle_multi_images')
            ->where('vehicle_id','=',$vehicle_id)
            ->get();
             $get_post_data = DB::table('posts')
                ->where([
                    ['post_id','=',$post_id],
                    ['status','=','Approved']
                ])
                ->get();
                foreach($get_post_data as $post_data){
                    $rider_email = $post_data->author;
                }
            $view_review = DB::table('rattings')
            ->where('rider','=',$rider_email)
            ->paginate('10');
             return view('pages.backend.dashboard.helping-page.user.booking-details',compact('user_status','post_details','vehicle_details','vehicle_multi_image','view_review'));
        }else{
            return redirect()->back()->with('error','Sorry, Trip is no longer available');
        }
    }

    public function SetBooking(Request $request){
          $validateInputData = $request->validate([
           'qtyValue' => 'max:255|numeric|min:1',
        ],[
            'qtyValue.numeric' =>'The seat must be a number.',
            'qtyValue.min' =>'You must select atleast one seat to travel.'
        ]);
        $post_id = $request->post_id;
        $individual_book = $request->qtyValue;
        $today = date('Y-m-d');
        $author = Auth::user()->email;
        $verify_request_approval = DB::table('user_cancelled_posts')
        ->where([
            ['cancelled_by','=',$author],
            ['timeline','=',$today]
            ])
        ->count();
        if($verify_request_approval >= 3){
            return redirect()->route('/')->with('error','Your trip request blocked for today due to multiple cancellation');
        }else{
        $verify_availability = DB::table('posts')
        ->where([
            ['post_id','=',$post_id],
            ['seat','>','0']
            ])
        ->count();
        $fetch_seats_from_post = DB::table('posts')
        ->where('post_id','=',$post_id)
        ->get();
        foreach ($fetch_seats_from_post as $fetch_seats) {
            $post_seats = $fetch_seats->seat;
            $fare_price_per_seat = $fetch_seats->price_per_seat;
            $from_loc = $fetch_seats->from_loc;
            $to_loc = $fetch_seats->to_loc;
            $jurney_date = $fetch_seats->jurney_date;
        };
        $booking_id = IdGenerator::generate(['table' => 'bookings', 'field' => 'booking_id', 'length' => 12, 'prefix' => 'BN-']);
        if($verify_availability === 1){
                if($request->has('book_all')){
                $seat_booked = $post_seats;
                $total_fare_cost_per_seat = $fare_price_per_seat * $seat_booked;
                $update_post = [
                    'seat' => '0',
                    'total_fare' => '0',
                    'updated_at' => Carbon::now()
                ];
                DB::table('posts')
                ->where('post_id','=',$post_id)
                ->update($update_post);
                Booking::insert([
                    'booking_id' => $booking_id,
                    'post_id' => $post_id,
                    'seat_booked' => $seat_booked,
                    'total_fare_cost' => $total_fare_cost_per_seat,
                    'booked_by' => $author,
                    'created_at' => Carbon::now()
                ]);
            }else{
                $seat_booked = $individual_book;
                    $total_fare_cost_per_seat = $fare_price_per_seat * $seat_booked;
                    $remaining_seats = $post_seats - $seat_booked;
                    $remaining_seats_total_fare = $remaining_seats * $fare_price_per_seat;
                     $verify_booked_seat_availability = DB::table('posts')
                    ->where('post_id','=',$post_id)
                    ->where('seat', '>=',$seat_booked)
                    ->count();
                    if($verify_booked_seat_availability === 1){
                         $update_post = [
                        'seat' => $remaining_seats,
                        'total_fare' => $remaining_seats_total_fare,
                        'updated_at' => Carbon::now()
                    ];
                        DB::table('posts')
                        ->where('post_id','=',$post_id)
                        ->update($update_post);
                        Booking::insert([
                            'booking_id' => $booking_id,
                            'post_id' => $post_id,
                            'seat_booked' => $seat_booked,
                            'total_fare_cost' => $total_fare_cost_per_seat,
                            'booked_by' => $author,
                            'created_at' => Carbon::now()
                        ]);
                    }else{
                        return redirect()->route('/')->with('error','Seat requested total seat not available');
                    }
                }
                $req_date = date('Y-m-d');
                $fetch_vehicle = DB::table('posts')
                ->join('vehicle_details','posts.vehicle_id','vehicle_details.vehicle_id')
                ->where('posts.post_id','=',$post_id)
                ->select('vehicle_details.*','posts.author As rider')
                ->get();
                foreach($fetch_vehicle as $vehicle){
                    $rider = $vehicle->rider;
                    $plate = $vehicle->plate;
                    $model = $vehicle->model;
                }
                 Tracking::insert([
                    'booking_id'=> $booking_id,
                    'post_id'  => $post_id,
                    'booked_by' => $author,
                    'rider_email' => $rider,
                    'status' => 'Pending',
                    'created_at' => Carbon::now()
                ]);
                  $data=['author'=> $author,
                    'car_model' => $model,
                    'plate' => $plate,
                    'seat_booked' => $seat_booked,
                    'jurney_date' => $jurney_date,
                    'from_loc' => $from_loc,
                    'to_loc' => $to_loc,
                    'status' => 'Pending',
                    'req_date' => $req_date
                    ];
                $user['to']=$rider;
                Mail::send('emails.travel-request-mail',$data,function($messages)use($user){
                    $messages->to($user['to']);
                    $messages->subject('Request for vehicle approval');
                });
                return redirect()->route('/')->with('success','Travel request send successfully. Please wait till your rider pick the offer');
            }else{
                return redirect()->route('/')->with('error','Sorry Trip not available');
            }
        }
    }

    /**Helping Function*/
    public function FetchUSerStatus(Request $request){
        $author = $request->author;
        $track_existance = DB::table('trackings')
        ->where('booked_by','=',$author)
        ->where('status','!=','Paid')
        ->count();
        if($track_existance === 1){
            $current_data = DB::table('trackings')
            ->where('booked_by','=',$author)
            ->get();
            foreach ($current_data as $k => $tracking){
                    $status = $tracking->status;
                    $rider_email = $tracking->rider_email;
                    $booking_id = $tracking->booking_id;
            }
            if($status === 'Pending'){
                return response()->json([
                    'track_status' => 'exist',
                    'html' => view('components.status.pending')->render()
                ],200);
            }else if ($status === 'Rider accepted') {
                return response()->json([
                        'track_status' => 'exist',
                        'html' => view('components.status.accepted')->render()
                    ],200);
            }elseif($status === 'Rider reaching'){
                return response()->json([
                        'track_status' => 'exist',
                        'html' => view('components.status.reaching-rider')->render()
                    ],200);
            }elseif($status === 'Rider reached'){
                return response()->json([
                        'track_status' => 'exist',
                        'html' => view('components.status.rider-reached')->render()
                    ],200);
            }elseif($status === 'Journey Stated'){
                return response()->json([
                        'track_status' => 'exist',
                        'html' => view('components.status.started-journey')->render()
                    ],200);
            }elseif($status === 'Reached Destination'){
                return response()->json([
                        'track_status' => 'exist',
                        'html' => view('components.status.reached-destination')->render()
                    ],200);
            }elseif($status === 'Complete'){
                return response()->json([
                        'track_status' => 'exist',
                        'status_trip' => 'Complete',
                        'rider_email' => $rider_email,
                        'booking_id' => $booking_id
                    ],200);
            }
        }else{
             return response()->json([
                'track_status' => 'not exist'
            ],200);
        }
    }

    /**Delete Trip*/
    public function DeleteTrip(Request $request){
        $booking_id = $request->booking_id;
        $status = $request->status;
        $today = date('Y-m-d');
        $author = Auth::user()->email;
        if($status === 'Pending'){
           $get_booking_details = DB::table('bookings')
            ->where([
                ['booking_id','=',$booking_id],
                ['status','=',$status]
            ])
            ->get();
              foreach($get_booking_details as $booking_details){
                $post_id = $booking_details->post_id;
                $seat_booked = $booking_details->seat_booked;
              }
            $get_post = DB::table('posts')
            ->where('post_id','=',$post_id)
            ->get();
            foreach($get_post as $post){
                $fare_per_seat = $post->price_per_seat;
                $vehicle_id = $post->vehicle_id;
                $rest_seat = $post->seat;
            }
            $get_vehicle_details = DB::table('vehicle_details')
            ->where('vehicle_id','=',$vehicle_id)
            ->get();
            foreach($get_vehicle_details as $vehicle_details){
                $total_seats = $vehicle_details->seats;
            }
            if($total_seats >= $seat_booked){
                $total_fare = ($rest_seat+$seat_booked)*$fare_per_seat;
                $new_seat = $rest_seat+$seat_booked;
                DB::table('posts')
                ->where('post_id','=',$post_id)
                ->update([
                    'seat'=>$new_seat,
                    'total_fare'=>$total_fare,
                    'updated_at' => Carbon::now()
                ]);
                DB::table('bookings')
                ->where('booking_id','=',$booking_id)
                ->delete();
                    DB::table('user_cancelled_posts')
                    ->insert([
                        'post_id' => $post_id,
                        'cancelled_by'=> $author,
                        'timeline'=> $today,
                        'created_at'=> Carbon::now()
                    ]);
            }
            DB::table('trackings')
            ->where([
                ['booking_id','=',$booking_id],
                ['booked_by','=',$author]
            ])
            ->delete();
            return redirect()->back()->with('success','Trip Cancelled successfully');
        }else{
            return redirect()->back()->with('error','Sorry you can not cancel the booking at this moment');
        }
    }

    public function StoreRank(Request $request){
        $rider_email = $request->rider_email;
        $booking_id = $request->booking_id;
        $comment =  $request->comment;
        $rating =  $request->rating;
        $author = Auth::user()->email;

        $fetch_prev_reting = DB::table('rattings')
        ->where('rider','=',$rider_email)
        ->select('rate')
        ->count();
        $fetch_prev_reting_client = DB::table('rattings')
        ->where('rider','=',$rider_email)
        ->count();
        if($fetch_prev_reting <= 0){
            Ratting::insert([
                'comment'=>$comment,
                'rate'=>$rating,
                'rider'=>$rider_email,
                'author'=>$author,
                'created_at' => Carbon::now()
            ]);
             DB::table('rider_details')
              ->where('email','=',$rider_email)
              ->update([
                'rating' =>$rating,
                'updated_at' => Carbon::now()
              ]);
        }else{
            $new_rating = ((($fetch_prev_reting+$rating)*$fetch_prev_reting_client)/5);
            Ratting::insert([
                'comment'=>$comment,
                'rate'=>$rating,
                'rider'=>$rider_email,
                'author'=>$author,
                'created_at' => Carbon::now()
            ]);
             DB::table('rider_details')
              ->where('email','=',$rider_email)
              ->update([
                'rating' =>$new_rating,
                'updated_at' => Carbon::now()
              ]);
        }
        DB::table('bookings')
              ->where('booking_id','=',$booking_id)
              ->update([
                'status' =>'Paid',
                'updated_at' => Carbon::now()
              ]);
         DB::table('trackings')
              ->where('booking_id','=',$booking_id)
              ->update([
                'status' =>'Paid',
                'updated_at' => Carbon::now()
              ]);
        return redirect()->back()->with('success','Thank you for your opinion');
    }

    public function NoRatting(Request $request){
         $booking_id = $request->bokking_no_rating;
          DB::table('bookings')
              ->where('booking_id','=',$booking_id)
              ->update([
                'status' =>'Paid',
                'updated_at' => Carbon::now()
              ]);
         DB::table('trackings')
              ->where('booking_id','=',$booking_id)
              ->update([
                'status' =>'Paid',
                'updated_at' => Carbon::now()
              ]);
        return redirect()->back()->with('success','Thank you for travalling with us');
    }
}

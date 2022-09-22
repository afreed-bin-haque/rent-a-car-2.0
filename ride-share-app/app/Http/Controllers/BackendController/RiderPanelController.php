<?php

namespace App\Http\Controllers\BackendController;

use App\Http\Controllers\Controller;
use App\Models\VehicleDetail;
use App\Models\VehicleMultiImage;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Mockery\Matcher\Ducktype;
use App\Models\Post;

class RiderPanelController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function ViewPanel(Request $request){
        $serach_vehicle =$request->query('car_query');
        $author = Auth::user()->email;
        $fetch_user_details = DB::table('app_user_roles')
        ->where('email', '=', Auth::user()->email)
            ->get();
        foreach ($fetch_user_details as $user_details) {
            $user_status = $user_details->role;
        }
        if (!empty($serach_vehicle)) {
            $vehicle_list = DB::table('vehicle_details')
            ->where('model', 'LIKE', "%$serach_vehicle%")
            ->where('author', '=', $author)
                ->orderBy('vehicle_id', 'DESC')
                ->paginate('4');
        } else {
            $vehicle_list = DB::table('vehicle_details')
            ->where('author', '=', $author)
                ->orderBy('vehicle_id', 'DESC')
                ->paginate('4');
        }
        $vehicle_type_list = DB::table('vehicle_types')
        ->get();
        return view('pages.backend.dashboard.helping-page.rider.vehicle-panel',compact('user_status', 'vehicle_list', 'vehicle_type_list'));
    }

    public function StoreVehicle(Request $request){
        $validateInputData = $request->validate([
            'mileage' => 'required|max:255',
            'plate' => 'required|max:255|unique:vehicle_details,plate',
            'condition' => 'required|max:255',
            'model' => 'required|max:255',
            'seats' => 'required|numeric|min:2|max:255',
            'color' => 'required|max:255',
            'type' => 'required|max:255',
            'main_image' => 'required|mimes:jpg,jpeg,png',
            'multi_image.*' => 'mimes:jpg,jpeg,png',
        ]);
        $input_mileage = $request->mileage;
        $mileage = $input_mileage.' KM';
        $plate = $request->plate;
        $condition = $request->condition;
        $model = $request->model;
        $type = $request->type;
        $seats = $request->seats;
        $color = $request->color;
        $main_image = $request->main_image;
        $multi_image = $request->multi_image;
        $author = Auth::user()->email;
        $request_date =  date('d-m-Y');
        $vehicle_id = IdGenerator::generate(['table' => 'vehicle_details', 'field' => 'vehicle_id', 'length' => 12, 'prefix' => 'VN-']);
         if(!empty($main_image)){
                    $file_name_gen = hexdec(uniqid()).'.'. $main_image->getClientOriginalExtension();
                    Image::make($main_image)->resize(400,400)->save('public/service_assets/vehicle_image/'.$file_name_gen);
                    $final_car_image = '/public/service_assets/vehicle_image/'.$file_name_gen;
                    VehicleDetail::insert([
                    'vehicle_id' => $vehicle_id,
                    'mileage' => $mileage,
                    'plate' => $plate,
                    'condition' => $condition,
                    'model' => $model,
                    'type' => $type,
                    'seats' => $seats,
                    'color' => $color,
                    'main_image' => $final_car_image,
                    'author' => $author,
                     'created_at' => Carbon::now()
                    ]);
         }else{
            VehicleDetail::insert([
                'vehicle_id' => $vehicle_id,
                'mileage' => $mileage,
                'plate' => $plate,
                'condition' => $condition,
                'model' => $model,
                'seats' => $seats,
                'color' => $color,
                'created_at' => Carbon::now()
            ]);
         }
        if (!empty($multi_image)) {
            for ($count = 0; $count < count($multi_image); $count++) {
                $file_name_gen = hexdec(uniqid()) . '.' . $multi_image[$count]->getClientOriginalExtension();
                Image::make($multi_image[$count])->resize(720, 1280)->save('public/service_assets/vehicle_image/internal/' . $file_name_gen);
                $final_car_interior_path = '/public/service_assets/vehicle_image/internal/' . $file_name_gen;
                VehicleMultiImage::insert([
                    'vehicle_id' => $vehicle_id,
                    'img_description' => $model,
                    'img_path' => $final_car_interior_path,
                    'created_at' => Carbon::now()
                ]);
            }
        }
        $data=['author'=> $author,
            'car_model' => $model,
            'plate' => $plate,
            'date' => $request_date
            ];
        $user['to']='admin@ride_share.com';
        Mail::send('emails.vehicle-mail',$data,function($messages)use($user){
            $messages->to($user['to']);
            $messages->subject('Request for vehicle approval');
        });
        return redirect()->route('vehicle.registrationPanel')->with('success', 'Vehicle Registration Successful Waiting for Aproval');
    }

    /**Trip*/
    public function PostTrip(Request $request){
          $validateInputData = $request->validate([
            'from_city' => 'required',
            'to_city' => 'required',
            'plate' => 'required',
            'year' => 'required|numeric|digits:4',
            'month' => 'required|numeric|digits:2|gt:0|lt:13',
            'date' => 'required|numeric|digits:2|gt:0|lt:32',
            'single_seat_fare' => 'required|numeric',
        ]);
        $from_city = $request->from_city;
        $to_city = $request->to_city;
        $plate_vehicle_id = $request->plate;
        $year = $request->year;
        $month = $request->month;
        $date = $request->date;
        $booking_date = $year.'-'.$month.'-'.$date;
        $today = Date('Y-m-d');
        if($booking_date >= $today){
            $single_seat_fare = $request->single_seat_fare;
            $fetch_vehicle_details = DB::table('vehicle_details')
                ->where('vehicle_id','=',$plate_vehicle_id)
                ->get();
                foreach($fetch_vehicle_details as $vehicle_details){
                    $total_seat = $vehicle_details->seats;
                    $vehicle_plate = $vehicle_details->plate;
                }
            $verify_vehicle_availability_booking = DB::table('posts')
            ->join('bookings','posts.post_id','bookings.post_id')
            ->where([
                ['posts.plate','=',$vehicle_plate],
                ['posts.jurney_date','=',$booking_date],
                ['bookings.status','!=','Complete']
                ])
            ->count();
            $verify_vehicle_availability = DB::table('posts')
            ->where([
                ['plate','=',$vehicle_plate],
                ['jurney_date','=',$booking_date],
                ])
            ->count();
            if(($verify_vehicle_availability === 1) || ($verify_vehicle_availability_booking === 1)){
                return redirect()->back()->with('error', 'This vehicle is already in trip, please select other vehicle');
            }else{
                $total_fare = ceil($total_seat * $single_seat_fare);
                $post_id = IdGenerator::generate(['table' => 'posts', 'field' => 'post_id', 'length' => 12, 'prefix' => 'POST-']);
                    Post::insert([
                        'post_id' => $post_id,
                        'from_loc' => $from_city,
                        'to_loc' => $to_city,
                        'vehicle_id' => $plate_vehicle_id,
                        'plate' => $vehicle_plate,
                        'jurney_date' => $booking_date,
                        'seat' => $total_seat,
                        'price_per_seat' => $single_seat_fare,
                        'total_fare' => $total_fare,
                        'author' => Auth::user()->email,
                        'created_at' => Carbon::now()
                    ]);
                DB::table('posts')
                ->where([
                    ['author' ,'=', Auth::user()->email],
                    ['jurney_date' ,'<', $today]
                    ])
                ->delete();
                return redirect()->back()->with('success', 'Trip Registration Successful');
            }
        }else{
             return redirect()->back()->with('error', 'Trip date must be greater or equal to current date');
        }
    }

    public function ChangeTripStatus(Request $request){
         $booking_id = $request->booking_id;
         $status = $request->status;
         $trip_0 = 'Pending';
         $trip_1 = 'Rider accepted';
         $trip_2 = 'Rider reaching';
         $trip_3 = 'Rider reached';
         $trip_4 = 'Journey Stated';
         $trip_5 = 'Reached Destination';
         $trip_6 = 'Complete';
         if($status == $trip_0){
            DB::table('bookings')
            ->where('booking_id','=',$booking_id)
            ->update([
                'status'=>$trip_1,
                'updated_at' => Carbon::now()
            ]);
            DB::table('trackings')
            ->where('booking_id','=',$booking_id)
            ->update([
                'status'=>$trip_1,
                'updated_at' => Carbon::now()
            ]);
         }elseif($status == $trip_1){
             DB::table('bookings')
            ->where('booking_id','=',$booking_id)
            ->update([
                'status'=>$trip_2,
                'updated_at' => Carbon::now()
            ]);
            DB::table('trackings')
            ->where('booking_id','=',$booking_id)
            ->update([
                'status'=>$trip_2,
                'updated_at' => Carbon::now()
            ]);
         }elseif($status == $trip_2){
             DB::table('bookings')
            ->where('booking_id','=',$booking_id)
            ->update([
                'status'=>$trip_3,
                'updated_at' => Carbon::now()
            ]);
            DB::table('trackings')
            ->where('booking_id','=',$booking_id)
            ->update([
                'status'=>$trip_3,
                'updated_at' => Carbon::now()
            ]);
         }elseif($status == $trip_3){
             DB::table('bookings')
            ->where('booking_id','=',$booking_id)
            ->update([
                'status'=>$trip_4,
                'updated_at' => Carbon::now()
            ]);
            DB::table('trackings')
            ->where('booking_id','=',$booking_id)
            ->update([
                'status'=>$trip_4,
                'updated_at' => Carbon::now()
            ]);
         }elseif($status == $trip_4){
             DB::table('bookings')
            ->where('booking_id','=',$booking_id)
            ->update([
                'status'=>$trip_5,
                'updated_at' => Carbon::now()
            ]);
            DB::table('trackings')
            ->where('booking_id','=',$booking_id)
            ->update([
                'status'=>$trip_5,
                'updated_at' => Carbon::now()
            ]);
         }elseif($status == $trip_5){
             DB::table('bookings')
            ->where('booking_id','=',$booking_id)
            ->update([
                'status'=>$trip_6,
                'updated_at' => Carbon::now()
            ]);
            DB::table('trackings')
            ->where('booking_id','=',$booking_id)
            ->update([
                'status'=>$trip_6,
                'updated_at' => Carbon::now()
            ]);
         }
          return redirect()->back()->with('success', 'Trip Status Updated Successfully');
    }

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
                $booked_by = $booking_details->booked_by;
              }
            $get_post = DB::table('posts')
            ->where('post_id','=',$post_id)
            ->get();
            foreach($get_post as $post){
                 $from_loc = $post->from_loc;
                 $to_loc = $post->to_loc;
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
            }
            DB::table('trackings')
            ->where([
                ['booking_id','=',$booking_id],
                ['post_id' ,'=', $post_id,]
            ])
            ->delete();
             $data=['author'=> $author,
            'from_loc' => $from_loc,
            'to_loc' => $to_loc,
            'user' => $booked_by
            ];
                $user['to']=$booked_by;
                Mail::send('emails.trip-cancelled',$data,function($messages)use($user){
                    $messages->to($user['to']);
                    $messages->subject('Trip request cancelled');
                });

            return redirect()->back()->with('success','Trip Cancelled successfully');
        }else{
            return redirect()->back()->with('error','Sorry you can not cancel the booking at this moment');
        }
    }
}

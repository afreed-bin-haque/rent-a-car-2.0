<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemAppPayloadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function FetchFromCity(Request $request)
    {
        $selected_from_city = $request->selected_from_city;
        if(empty($selected_from_city)){
            $fetch_to_city = 'Select your starting point first';
        }else{
            $fetch_to_city = DB::table('cities_tables')
            ->where('name', '!=', $selected_from_city)
            ->get();
        }
        return response()->json([
            'fetched_to_cities' => $fetch_to_city
        ], 200);
    }

    /**Fetch Image*/
    public function FetchCarMainImage(Request $request){
        $vehicle_id = $request->vehicle_id;
          if(empty($vehicle_id)){
            $fetch_car_main_image = 'Select vehicle plate no first';
        }else{
             $fetch_car_main_image = DB::table('vehicle_details')
            ->where('vehicle_id', '=', $vehicle_id)
            ->get();
        }
         return response()->json([
            'image' => $fetch_car_main_image
        ], 200);
    }
}

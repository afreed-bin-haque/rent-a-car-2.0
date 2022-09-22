<?php

namespace App\Http\Controllers\APIControllers;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\APIUser;
use App\Models\CitiesTable;
use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AppPayloadController extends Controller
{
    /**API Index */
    public function Index(){
        $start = microtime(true);
        $app_name = config('apiConfig.api.name');
        $version = config('apiConfig.api.version');
        $api_details = collect();
        $api_details->push([
            'App_name' => $app_name,
            'Version' => $version,
        ]);
        $last_time= microtime(true);
        $execution_time = ($last_time - $start)*1000 .' Milisecond';
        return response()->json([
            'status' => 'Accepted',
            'app_details' => $api_details,
            'execution_time' => $execution_time
        ],200);
    }

    /**Generate API User */
    public function GenerateApiUser(Request $request){
        try {
            $header_user = $request->header('user');
            $header_token = $request->header('token');

            $verify_author= Helpers::VerifyTokenRoot($header_user,$header_token);
            if($verify_author === 'Accepted'){
                $validator = Validator::make($request->all(), [
                    'user_name' => 'required|max:255',
                    'access' => 'required|in:Backend,Frontend|max:255',
                    'limit' => 'required|in:Unlimited,Limited|max:255',
                    'limit_range' => 'numeric|max:255',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'Bad request',
                        'error_message' => $validator->messages(),
                    ], 400);
                } else {
                    $start = microtime(true);
                    $user_name = $request->user_name;
                    $lower_case_name = strtolower($user_name);
                    $slugged_name = Str::slug($lower_case_name);
                    $verify_api_use = DB::table('a_p_i_users')
                        ->where('api_user', '=', $slugged_name)
                        ->count();
                        if($verify_api_use === 1){
                            return response()->json([
                                'status' => 'Bad request',
                                'error_message' => 'This user already registerd',
                            ], 400);
                        }else{
                            $access = $request->access;
                            $limit = $request->limit;
                            $request_limit_range = $request->limit_range;
                            $limit_range = ltrim($request_limit_range, "0");
                            $token = Helpers::generateToken();
                            APIUser::insert([
                                'api_user' => $slugged_name,
                                'token' => $token,
                                'access' => $access,
                                'limit' => $limit,
                                'limit_range' => $limit_range,
                                'created_at' => Carbon::now()
                            ]);
                            $data_details = collect();
                            $fetch_details = DB::table('a_p_i_users')
                            ->where('api_user', '=', $slugged_name)
                            ->where('token', '=', $token)
                            ->get();
                            foreach($fetch_details as $deatils){
                                $data_details->push([
                                    'api_user' => $deatils->api_user,
                                    'token' => $deatils->token,
                                    'access' => $deatils->access,
                                    'status' => $deatils->status,
                                ]);
                            }
                            $last_time = microtime(true);
                            $execution_time = ($last_time - $start) * 1000000 . ' second';
                            return response()->json([
                                'status' => 'Accepted',
                                'response_data_details' => $data_details,
                                'execution_time' => $execution_time
                            ], 200);
                        }
                }
            }else{
                return response()->json([
                    'Status' => 'Access forbidden',
                    'Message' => 'You are not allowed to access',
                    'Error' => 'Credentials Missing',
                ], 403);
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            return response([
                'Status' => 'Unprocessable Entity',
                'Error' => $error,
            ], 422);
        }
    }

    /**Store Cities */
    public function StoreCities(Request $request){
        try {
            $header_user = $request->header('user');
            $header_token = $request->header('token');
            $verify_author = Helpers::VerifyTokenRoot($header_user, $header_token);
            if ($verify_author === 'Accepted') {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'Bad request',
                        'error_message' => $validator->messages(),
                    ], 400);
                } else {
                    $start = microtime(true);
                    $name = $request->name;
                    $upper_case_name = strtoupper($name);
                    $lower_case_name = strtolower($name);
                    $slugged = Str::slug($lower_case_name);
                    $verify_entry = DB::table('cities_tables')
                    ->where('slug', '=', $slugged)
                    ->count();
                    if($verify_entry === 1){
                        return response()->json([
                            'status' => 'Bad request',
                            'error_message' => 'This city already exists',
                        ], 400);
                    }else{
                        CitiesTable::insert([
                            'name' => $upper_case_name,
                            'slug' => $slugged,
                            'created_at' => Carbon::now()
                        ]);
                        $data_details = collect();
                        $data_fetch = DB::table('cities_tables')
                        ->get();
                        foreach($data_fetch as $data){
                            $data_details->push([
                                'city' => $data->name,
                                'slugged_city' => $data->slug
                            ]);
                        }
                        $last_time = microtime(true);
                        $execution_time = ($last_time - $start) * 1000000 . ' second';
                        return response()->json([
                            'status' => 'Accepted',
                            'response_data_details' => $data_details,
                            'execution_time' => $execution_time
                        ], 200);
                    }
                }
            } else {
                return response()->json([
                    'Status' => 'Access forbidden',
                    'Message' => 'You are not allowed to access',
                    'Error' => 'Credentials Missing',
                ], 403);
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            return response([
                'Status' => 'Unprocessable Entity',
                'Error' => $error,
            ], 422);
        }

    }

    /**Get cities */
    public function GetCities(Request $request){
        try {
            $header_user = $request->header('user');
            $header_token = $request->header('token');

            $verify_author = Helpers::VerifyTokenExternal($header_user, $header_token);
            if($verify_author === 'Accepted'){
                $start = microtime(true);
                $lower_case_name = strtolower($header_user);
                $slugged_name = Str::slug($lower_case_name);
                $limit_fetch = DB::table('a_p_i_users')
                    ->where('api_user', '=', $slugged_name)
                    ->where('token', '=', $header_token)
                    ->where('access', '=', 'Frontend')
                    ->where('status', '=', 'Active')
                    ->where('limit', '=', 'Limited')
                    ->get();
                foreach ($limit_fetch as $limit) {
                    $limit_range = $limit->limit_range;
                    $limit_count = $limit->limit_count;
                }
                $new_limit_count = $limit_count + 1;
                $verify_limit = DB::table('a_p_i_users')
                ->where('api_user', '=', $slugged_name)
                ->where('token', '=', $header_token)
                ->where('access', '=', 'Frontend')
                ->where('status', '=', 'Active')
                ->where('limit', '=', 'Limited')
                ->where('limit_range', '>=', $new_limit_count)
                ->count();
                if($verify_limit === 1){
                    $data_details = collect();
                    $data_fetch = DB::table('cities_tables')
                        ->get();
                    foreach ($data_fetch as $data) {
                        $data_details->push([
                            'city' => $data->name,
                        ]);
                    }
                    $update_data = [
                        'limit_count' => $new_limit_count,
                        'updated_at' => Carbon::now()
                    ];
                      DB::table('a_p_i_users')
                        -> where('api_user', '=', $slugged_name)
                        ->where('token', '=', $header_token)
                        ->where('access', '=', 'Frontend')
                        ->where('status', '=', 'Active')
                        ->where('limit', '=', 'Limited')
                        ->update($update_data);
                    $last_time = microtime(true);
                    $execution_time = ($last_time - $start) * 1000000 . ' second';
                    return response()->json([
                        'status' => 'Accepted',
                        'response_data_details' => $data_details,
                        'limit_range' => $limit_range,
                        'requested_limit_range' => $new_limit_count,
                        'execution_time' => $execution_time
                    ], 200);
                }else{
                    return response()->json([
                        'Status' => 'Access forbidden',
                        'Message' => 'You have crossed your access limit',
                        'limit_range' => $limit_range,
                        'requested_limit_range' => $new_limit_count,
                    ], 403);
                }
            }else{
                return response()->json([
                    'Status' => 'Access forbidden',
                    'Message' => 'You are not allowed to access',
                    'Error' => 'Credentials Missing',
                ], 403);
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            return response([
                'Status' => 'Unprocessable Entity',
                'Error' => $error,
            ], 422);
        }
    }


    /**Store vahicle type */
    public function StoreVehicleType(Request $request){
        try {
            $header_user = $request->header('user');
            $header_token = $request->header('token');
            $verify_author = Helpers::VerifyTokenRoot($header_user, $header_token);
            if ($verify_author === 'Accepted') {
                $validator = Validator::make($request->all(), [
                    'type' => 'required|max:255',
                    'minimum_seat' => 'required|max:255',
                    'maximum_seat' => 'required|max:255',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'Bad request',
                        'error_message' => $validator->messages(),
                    ], 400);
                } else {
                    $start = microtime(true);
                    $type = strtoupper($request->type);
                    VehicleType::insert([
                        'type' => $type,
                        'created_at' => Carbon::now()
                    ]);
                    $data_details = collect();
                    $data_fetch = DB::table('vehicle_types')
                    ->get();
                    foreach ($data_fetch as $data) {
                        $data_details->push([
                            'type' => $data->type,
                            'minimum_seat' => $data->minimum_seat,
                            'maximum_seat' => $data->maximum_seat,
                        ]);
                    }
                    $last_time = microtime(true);
                    $execution_time = ($last_time - $start) * 1000000 . ' second';
                    return response()->json([
                        'status' => 'Accepted',
                        'response_data_details' => $data_details,
                        'execution_time' => $execution_time
                    ], 200);
                }
            } else {
                return response()->json([
                    'Status' => 'Access forbidden',
                    'Message' => 'You are not allowed to access',
                    'Error' => 'Credentials Missing',
                ], 403);
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            return response([
                'Status' => 'Unprocessable Entity',
                'Error' => $error,
            ], 422);
        }
    }
}

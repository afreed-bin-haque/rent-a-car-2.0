<?php

namespace App\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Helpers{
    /**Genenrate Token */
    public static function generateToken(){
        $length = 80;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString = $characters[rand(0, $charactersLength - 1)];
        }
        $current_time = microtime(true);
        $ceil_time = ceil($current_time * 1000);
        $token_enc = Hash::make(md5($randomString . $ceil_time));
        return $token_enc;
    }

    /**Verify Api Token */
    //Backend user
    public static function VerifyTokenRoot($user, $token){
        $lower_case_name = strtolower($user);
        $slugged_name = Str::slug($lower_case_name);
        $verify_token = DB::table('a_p_i_users')
            ->where('api_user', '=', $slugged_name)
            ->where('token', '=', $token)
            ->where('access', '=', 'Backend')
            ->where('status', '=', 'Active')
            ->count();
        if ($verify_token === 1) {
            $permission = 'Accepted';
        } else {
            $permission = 'Denied';
        }
        return $permission;
    }
    //Frontend user
    public static function VerifyTokenExternal($user, $token){
        $lower_case_name = strtolower($user);
        $slugged_name = Str::slug($lower_case_name);
        $verify_token = DB::table('a_p_i_users')
        ->where('api_user', '=', $slugged_name)
            ->where('token', '=', $token)
            ->where('access', '=', 'Frontend')
            ->where('status', '=', 'Active')
            ->count();
        if ($verify_token === 1) {
            $permission = 'Accepted';
        } else {
            $permission = 'Denied';
        }
        return $permission;
    }
    /**Fetch user details*/
    public static function FetchUserRole(){
        $fetch_user_details = DB::table('app_user_roles')
        ->where('email', '=', Auth::user()->email)
            ->get();
        foreach ($fetch_user_details as $user_details) {
            $user_status = $user_details->role;
        }
        return $user_status;
    }
}

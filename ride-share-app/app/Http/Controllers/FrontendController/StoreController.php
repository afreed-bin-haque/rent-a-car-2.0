<?php

namespace App\Http\Controllers\FrontendController;

use App\Http\Controllers\Controller;
use App\Models\AppUserRole;
use App\Models\RiderDetails;
use App\Models\User;
use App\Models\UserDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    /**User store*/
    public function StoreUSer(Request $request){
        $validateInputData = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|max:255|unique:users,email',
            'nid' => 'required|max:255|unique:user_details,nid',
            'contact' => 'required|max:255|digits:11',
            'adreess' => 'required|max:255',
            'password' => 'required|max:255',
        ]);
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $full_name = $first_name.' '.$last_name;
        $email = $request->email;
        $nid = $request->nid;
        $contact = $request->contact;
        $adreess = $request->adreess;
        $password = $request->password;
        $role = 'User';
        $hashed_password = Hash::make($password);
        User::insert([
            'name' => $full_name,
            'email' => $email,
            'password' => $hashed_password,
            'created_at' => Carbon::now()
        ]);
        AppUserRole::insert([
            'name' => $full_name,
            'email' => $email,
            'role' => $role,
            'created_at' => Carbon::now()
        ]);
        UserDetails::insert([
            'name' => $full_name,
            'email' => $email,
            'nid' => $nid,
            'contact' => $contact,
            'address' => $adreess,
            'created_at' => Carbon::now()
        ]);
        return redirect()->route('login')->with('success', 'You have successfully registered');
    }

    /**Rider store */
    public function StoreRider(Request $request){
        $validateInputData = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|max:255|unique:users,email',
            'nid' => 'required|max:255|unique:rider_details,nid',
            'contact' => 'required|max:255|digits:11',
            'adreess' => 'required|max:255',
            'password' => 'required|max:255',
        ]);
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $full_name = $first_name . ' ' . $last_name;
        $email = $request->email;
        $nid = $request->nid;
        $contact = $request->contact;
        $adreess = $request->adreess;
        $password = $request->password;
        $role = 'Rider';
        $hashed_password = Hash::make($password);
        User::insert([
            'name' => $full_name,
            'email' => $email,
            'password' => $hashed_password,
            'created_at' => Carbon::now()
        ]);
        AppUserRole::insert([
            'name' => $full_name,
            'email' => $email,
            'role' => $role,
            'created_at' => Carbon::now()
        ]);
        RiderDetails::insert([
            'name' => $full_name,
            'email' => $email,
            'nid'=> $nid,
            'contact' => $contact,
            'address' => $adreess,
            'created_at' => Carbon::now()
        ]);
        return redirect()->route('login')->with('success', 'You have successfully registered');
    }
}

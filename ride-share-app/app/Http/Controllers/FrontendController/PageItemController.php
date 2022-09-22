<?php

namespace App\Http\Controllers\FrontendController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageItemController extends Controller
{
    public function RegisterPageRoute(){
        return view('pages.frontend.register');
    }
    public function RegisterRiderPageRoute(){
        return view('pages.frontend.rider-register');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class SiteController extends Controller
{

    public function index(Request $request){
        $title = "Sites Campinas";
        return view('site.index', compact('title'));
    }

    public function teste(Request $request){
        //dd($request->session());
        $request->session()->forget('tinder-tools');
        $tinder_tools = array (
            "tinder-id" => "5b22a4a3fe07453631d88ea8",
            "tinder-tools-id" => 6,
            "tinder-token" => "14b900f3-7ed9-497d-8e27-83c915ab71dd",
            "access-token-get-at" => Carbon::now(),
            "birth_date" => null,
            "gender" => null,
            "name" => null,
            "photos" => null,
            "ping_time" => null,
            "city" => null,
            "country" => null
        );
        $request->session()->put('tinder-tools', $tinder_tools);
        return redirect('/tinder-tools');
    }

}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            "tinder-token" => "77184814-ae23-4405-a3fd-9285075c0f3c",
            "access-token-get-at" => "2018-08-15 20:43:46",
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
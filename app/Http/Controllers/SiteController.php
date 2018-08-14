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
        $request->session()->put('tinder-tools-id', 1);
        $request->session()->put('tinder-id', "5b4e2fba3dc09c0e2df558a5");
        $request->session()->put('tinder-token', "654dcc1b-9839-461c-b5cd-fef35ac3617f");
        $request->session()->put('access-token-get-at', "2018-08-03 12:00:09");
        return redirect('/tinder-tools/recs');
    }

}
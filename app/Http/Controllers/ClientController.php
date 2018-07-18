<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ClientController extends Controller
{
    
    public function index(){

        $users = DB::table('users')->get();
        //dd(json_decode($users[0]->photos)[0]->url);
        //dd(Carbon::today()->year);
        //dd(Carbon::today()->year - Carbon::parse($users[0]->birth_date)->year);
        return view('index', compact('users'));
        
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Profile;
use App\Logged_Profile;
use Validator;
use Illuminate\Validation\Rule;

class TinderLoginController extends Controller
{
    public function login(Request $request){
        return view('tinder-tools.login');
    }

    public function login_fb(Request $request){
        $profiles = Profile::with('logged_profile:id,lat,lon,birth_date,gender,city')
            ->orderBy('created_at', 'desc')
            ->paginate(24);
        return view('tinder-tools.login', compact('profiles'));

    }

    public function login_phone(Request $request){
        $profiles = Profile::with('logged_profile:id,lat,lon,birth_date,gender,city')
            ->orderBy('created_at', 'desc')
            ->paginate(24);
        return view('tinder-tools.index', compact('profiles'));

    }
    
}

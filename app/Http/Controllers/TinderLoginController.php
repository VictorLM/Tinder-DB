<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Logged_Profile;
use Validator;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class TinderLoginController extends Controller
{
    public function login(Request $request){
        $title = "| Login";
        return view('tinder-tools.login', compact('title'));
    }

    public function login_fb(Request $request){
        $title = "| Login Facebook";
        return view('tinder-tools.login_fb', compact('title'));
    }

    public function login_fb_post(Request $request){
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|max:50',
            'senha' => 'required|max:50'
        ]);

        if ($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput();
        }else{

            $process = new Process('C:/Python37/python.exe C:/Users/Victor/Desktop/tinder-python.py');
            $process->run();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            dd($process->getOutput());
            return view('tinder-tools.index', compact('profiles', 'nome', 'bio', 'idade', 'genero', 'instagram', 'orderby'));
            
        }
    }

    public function login_phone(Request $request){
        $profiles = Profile::with('logged_profile:id,lat,lon,birth_date,gender,city')
            ->orderBy('created_at', 'desc')
            ->paginate(24);
        return view('tinder-tools.index', compact('profiles'));

    }
    
}

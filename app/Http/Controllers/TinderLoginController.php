<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Logged_Profile;
use Validator;
use App\Services\PayUService\Exception;
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

    public function login_phone(Request $request){
        $title = "| Login Telefone";
        return view('tinder-tools.login_phone', compact('title'));
    }

    public function login_fb_post(Request $request){
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|max:50',
            'senha' => 'required|max:50'
        ]);

        if ($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput();
        }else{
            $process = new Process('python3 /apps/Tinder-DB/python/fb-login.py '.$request->email.' '.$request->senha);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
                return redirect()->back()->withErrors(['Ocorreu um erro. Verifique se os dados digitados estão corretos ou tente novamente mais tarde.'])->withInput();
            }else{
                if(strpos($process->getOutput(), 'error') !== false){
                    return redirect()->back()->withErrors(['Ocorreu um erro. Verifique se os dados digitados estão corretos ou tente novamente mais tarde.'])->withInput();
                }else{
                    $profile = $this->get_profile(str_replace("\n", "", $process->getOutput()));
                    if($profile){
                        $request->session()->put('tinder-tools-id', $profile->id);
                        $request->session()->put('tinder-id', $profile->tinder_id);
                        $request->session()->put('tinder-token', $profile->access_token);
                        $request->session()->put('access-token-get-at', $profile->access_token_get_at);
                        return redirect('/tinder-tools/recs');
                    }
                }
            }
        }
    }

    public function login_phone_post(Request $request){
        $validatedData = Validator::make($request->all(), [
            'phone' => 'required|string|max:15',
        ]);

        if ($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput();
        }else{
            $process = new Process('python3 /apps/Tinder-DB/python/phone-login-send-code.py '.$request->phone);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
                return redirect()->back()->withErrors(['Ocorreu um erro. Verifique se os dados digitados estão corretos ou tente novamente mais tarde.'])->withInput();
            }else{
                if(strpos($process->getOutput(), 'error') !== false){
                    return redirect()->back()->withErrors(['Ocorreu um erro. Verifique se os dados digitados estão corretos ou tente novamente mais tarde.'])->withInput();
                }else{
                    $title = "| Confirmar Telefone";
                    $phone = $request->phone;
                    $log_code = str_replace("\n", "", $process->getOutput());
                    return view('tinder-tools.confirm_phone', compact('title', 'phone', 'log_code'));
                    //dd($process->getOutput());
                    /*
                    $profile = $this->get_profile(str_replace("\n", "", $process->getOutput()));
                    if($profile){
                        $request->session()->put('tinder-tools-id', $profile->id);
                        $request->session()->put('tinder-id', $profile->tinder_id);
                        $request->session()->put('tinder-token', $profile->access_token);
                        $request->session()->put('access-token-get-at', $profile->access_token_get_at);
                        return redirect('/tinder-tools/recs');
                    }
                    */
                }
            }
        }
    }

    public function confirm_login_phone(Request $request){
        $validatedData = Validator::make($request->all(), [
            'code' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
            'log_code' => 'required|string|max:100'
        ]);

        if ($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput();
        }else{
            $process = new Process('python3 /apps/Tinder-DB/python/phone-login-confirm-code.py '.$request->log_code.' '.$request->phone.' '.$request->code);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
                return redirect()->back()->withErrors(['Ocorreu um erro. Verifique se os dados digitados estão corretos ou tente novamente mais tarde.'])->withInput();
            }else{
                if(strpos($process->getOutput(), 'error') !== false){
                    return redirect()->back()->withErrors(['Ocorreu um erro. Verifique se os dados digitados estão corretos ou tente novamente mais tarde.'])->withInput();
                }else{
                    $profile = $this->get_profile(str_replace("\n", "", $process->getOutput()));
                    if($profile){
                        $request->session()->put('tinder-tools-id', $profile->id);
                        $request->session()->put('tinder-id', $profile->tinder_id);
                        $request->session()->put('tinder-token', $profile->access_token);
                        $request->session()->put('access-token-get-at', $profile->access_token_get_at);
                        return redirect('/tinder-tools/recs');
                    }
                }
            }
        }
    }

    /*
    public function login_phone(Request $request){
        $profiles = Profile::with('logged_profile:id,lat,lon,birth_date,gender,city')
            ->orderBy('created_at', 'desc')
            ->paginate(24);
        return view('tinder-tools.index', compact('profiles'));
    }
    */
    //////////////////////////////////////////////////////////////////////////////////////////////
    function request($token, $url, $method, $body){
        $client = new Client();
        $headers = [
            'app_version'   => '6.9.4',
            'platform'      => 'ios',
            'content-type'  => 'application/json',
            'User-agent'    => 'Tinder/7.5.3 (iPhone; iOS 10.3.2; Scale/2.00)',
            'Accept'        => 'application/json',
            'X-Auth-Token'  => $token,
        ];
        try{
            $response = $client->request($method, 'https://api.gotinder.com/'.$url, [
                'headers' => $headers,
                'body' => $body
            ]);
            if($response->getStatusCode() == 200){
                $response = json_decode($response->getBody());
                return($response);
            }
        }catch (Exception $e){
            report($e);
            return false;
        }
    }
    //////////////////////////////////////////////////////////////////////////////////////////////
    function get_profile($token){
        $url = 'profile';
        $method = 'GET';
        $body = null;
        $profile = $this->request($token, $url, $method, $body);
        
        if($profile){
            $photos = array();
            $spotify = [];
            foreach($profile->photos as $photo){
                $photos[] = $photo->url;
            }
            if(isset($rec->spotify_theme_track->artists)){
                foreach($rec->spotify_theme_track->artists as $artist){
                    $spotify[] = $artist;
                }
            }
            $profile_id = Logged_Profile::updateOrCreate(
                [
                    'tinder_id' => $profile->_id, 
                    'age_filter_max' => $profile->age_filter_max, 
                    'age_filter_min' => $profile->age_filter_min, 
                    'distance_filter' => $profile->distance_filter, 
                    'gender' => $profile->gender,
                    'gender_filter' => $profile->gender_filter,
                    'interested_in' => json_encode($profile->interested_in),
                    'ping_time' => Carbon::parse($profile->ping_time)->format('Y-m-d H:i:s')
                ],
                [
                    'tinder_id' => $profile->_id ?? null,
                    'age_filter_max' => $profile->age_filter_max ?? null,
                    'age_filter_min' => $profile->age_filter_min ?? null,
                    'bio' => $profile->bio ?? null,
                    'birth_date' => Carbon::parse($profile->birth_date)->format('Y-m-d H:i:s') ?? null,
                    'create_date' => Carbon::parse($profile->create_date)->format('Y-m-d H:i:s') ?? null,
                    'distance_filter' => $profile->distance_filter ?? null,
                    'email' => $profile->email ?? null,
                    'facebook_id' => $profile->facebook_id ?? null,
                    'gender' => $profile->gender ?? null,
                    'gender_filter' => $profile->gender_filter ?? null,
                    'interested_in' => json_encode($profile->interested_in ?? null),
                    'name' => $profile->name ?? null,
                    'photos' => json_encode($photos) ?? null,
                    'instagram' => $profile->instagram->username ?? null,
                    'spotify' => json_encode($spotify) ?? null,
                    'ping_time' => Carbon::parse($profile->ping_time)->format('Y-m-d H:i:s') ?? null,
                    'full_pos_info' => json_encode($profile->pos_info ?? null),
                    'at' => $profile->pos->at ?? null,
                    'lat' => $profile->pos->lat ?? null,
                    'lon' => $profile->pos->lon ?? null,
                    'city' => $profile->pos_info->city->name ?? null,
                    'country' => $profile->pos_info->country->name ?? null,
                    'show_gender_on_profile' => $profile->show_gender_on_profile ?? null,
                    'can_create_squad' => $profile->can_create_squad ?? null,
                    'access_token' => $token ?? null,
                    'access_token_get_at' => Carbon::parse(Carbon::now())->format('Y-m-d H:i:s') ?? null,
                    'bot' => false,
                    'IP' => \Request::ip() ?? null
                ]
            );
            return $profile_id;
        }else{
            return false;
        }
        
    }
    
}
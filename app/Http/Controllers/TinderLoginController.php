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

    function is_logged(Request $request){
        if($request->session()->exists('tinder-tools') && Carbon::parse($request->session()->get('tinder-tools')['access-token-get-at'])->diffInHours(Carbon::now()) < 12){
            $logged_profile = Logged_Profile::find($request->session()->get('tinder-tools')['tinder-tools-id']);
            if(!empty($logged_profile)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function login(Request $request){
        if($this->is_logged($request)){
            return redirect('/tinder-tools');
        }else{
            $title = "| Login";
            return view('tinder-tools.login', compact('title'));
        }
    }

    public function login_fb(Request $request){
        if($this->is_logged($request)){
            return redirect('/tinder-tools');
        }else{
            $title = "| Login Facebook";
            return view('tinder-tools.login_fb', compact('title'));
        }
    }

    public function login_phone(Request $request){
        if($this->is_logged($request)){
            return redirect('/tinder-tools');
        }else{
            $title = "| Login Telefone";
            return view('tinder-tools.login_phone', compact('title'));
        }
    }

    public function login_fb_post(Request $request){
        if($this->is_logged($request)){
            return redirect('/tinder-tools');
        }else{
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
                            $tinder_tools = array (
                                "tinder-id" => $profile->tinder_id,
                                "tinder-tools-id" => $profile->id,
                                "tinder-token" => $profile->access_token,
                                "access-token-get-at" => $profile->access_token_get_at,
                                "birth_date" => $profile->birth_date,
                                "gender" => $profile->gender,
                                "name" => $profile->name,
                                "photos" => $profile->photos,
                                "ping_time" => $profile->ping_time,
                                "city" => $profile->city,
                                "country" => $profile->country
                            );
                            $request->session()->put('tinder-tools', $tinder_tools);
                            return redirect('/tinder-tools');
                        }
                    }
                }
            }
        }
    }

    public function login_phone_post(Request $request){
        if($this->is_logged($request)){
            return redirect('/tinder-tools');
        }else{
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
                    }
                }
            }
        }
    }

    public function confirm_login_phone(Request $request){
        if($this->is_logged($request)){
            return redirect('/tinder-tools');
        }else{
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
                            $tinder_tools = array (
                                "tinder-id" => $profile->tinder_id,
                                "tinder-tools-id" => $profile->id,
                                "tinder-token" => $profile->access_token,
                                "access-token-get-at" => $profile->access_token_get_at,
                                "birth_date" => $profile->birth_date,
                                "gender" => $profile->gender,
                                "name" => $profile->name,
                                "photos" => $profile->photos,
                                "ping_time" => $profile->ping_time,
                                "city" => $profile->city,
                                "country" => $profile->country
                            );
                            $request->session()->put('tinder-tools', $tinder_tools);
                            return redirect('/tinder-tools');
                        }
                    }
                }
            }
        }
    }
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

    public function logout(Request $request){
        $request->session()->forget('tinder-tools');
        return redirect('/tinder-tools');
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
                    'at' => $profile->pos->at,
                    'lat' => $profile->pos->lat,
                    'lon' => $profile->pos->lon
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
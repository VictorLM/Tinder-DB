<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Profile;
use App\Logged_Profile;
use App\Like;
use Validator;
use Illuminate\Validation\Rule;

class TinderController extends Controller
{
    public function __construct()
    {
        $this->middleware('App\Http\Middleware\token::class');
    }
    //////////////////////////////////////////////////////////////////////////////////////////////
    function request($url, $method, $body, $token){
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
    public function index(Request $request){
        $logged_profile_ids = Logged_Profile::where('tinder_id', $request->session()->get('tinder-tools')['tinder-id'])->pluck('id')->all();
        $liked_ids = Like::whereIn('logged_profile_id', $logged_profile_ids)->pluck('profile_id')->all();
        $profiles = Profile::with('logged_profile:id,lat,lon,birth_date,gender,city')
            ->whereNotIn('id', $liked_ids)
            ->whereIn('logged_profile_id', $logged_profile_ids)
            ->orderBy('created_at', 'desc')
            ->paginate(24);
        return view('tinder-tools.index', compact('profiles'));
    }

    public function ajax_recomendations(Request $request){
        //dd($request);
        return ($this->get_recomendations($request->session()->get('tinder-tools')['tinder-tools-id'], $request->session()->get('tinder-tools')['tinder-token']));//JSON
    }

    function get_recomendations($logged_profile_id, $token){
        $url = 'user/recs';
        $method = 'GET';
        $body = null;
        $recs = $this->request($url, $method, $body, $token);
        //dd($recs);
        if($recs){
            foreach($recs->results as $rec){
                $photos = [];
                $spotify = [];
                foreach($rec->photos as $photo){
                    $photos[] = $photo->url;
                }
                if(isset($rec->spotify_theme_track->artists)){
                    foreach($rec->spotify_theme_track->artists as $artist){
                        $spotify[] = $artist;
                    }
                }
                Profile::updateOrCreate(
                    ['tinder_id' => $rec->_id],
                    [
                        'logged_profile_id' => $logged_profile_id ?? null,
                        'tinder_id' => $rec->_id ?? null,
                        'group_matched' => $rec->group_matched ?? null,
                        'distance_mi' => $rec->distance_mi ?? null,
                        'content_hash' => $rec->content_hash ?? null,
                        'common_friends' => json_encode($rec->common_friends ?? null),
                        'common_likes' => json_encode($rec->common_likes ?? null),
                        'common_friend_count' => $rec->common_friend_count ?? null,
                        'common_like_count' => $rec->common_like_count ?? null,
                        'connection_count' => $rec->connection_count ?? null,
                        'bio' => $rec->bio ?? null,
                        'birth_date' => Carbon::parse($rec->birth_date)->format('Y-m-d H:i:s') ?? null,
                        'name' => $rec->name ?? null,
                        'ping_time' => Carbon::parse($rec->ping_time)->format('Y-m-d H:i:s') ?? null,
                        'photos' => json_encode($photos) ?? null,
                        'instagram' => $rec->instagram->username ?? null,
                        'spotify' => json_encode($spotify) ?? null,
                        'jobs' => json_encode($rec->jobs ?? null),
                        'schools' => json_encode($rec->schools ?? null),
                        'teasers' => json_encode($rec->teasers ?? null),
                        'gender' => $rec->gender ?? null,
                        'birth_date_info' => $rec->birth_date_info ?? null,
                        's_number' => $rec->s_number ?? null
                    ]
                );
            }
        }
    }


    public function search(Request $request){
        $validatedData = Validator::make($request->all(), [
            'nome' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:50',
            'idade' => 'nullable|integer|between:18,99',
            'genero' => [
                'nullable',
                Rule::in(['hm', 'hh', 'mh', 'mm', 'outros']),
            ],
            'instagram' => [
                'nullable',
                Rule::in(['instagrams', 'instagramn', 'todos']),
            ],
            'orderby' => [
                'nullable',
                Rule::in(['nomeaz', 'nomeza', 'idade01', 'idade10', 'distancia01', 'distancia10']),
            ]
        ]);

        if ($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput();
        }else{

            $profiles = Profile::with('logged_profile:id,lat,lon,gender,birth_date,city');
            $nome = null;
            $bio = null;
            $idade = null;
            $genero = null;
            $instagram = null;
            $orderby = null;
            if(!empty($request->nome)){
                $profiles->where('name', 'like', '%'.$request->nome.'%');
                $nome = $request->nome;
            }
            if(!empty($request->bio)){
                $profiles->where('bio', 'like', '%'.$request->bio.'%');
                $bio = $request->bio;
            }
            if(!empty($request->idade)){
                $profiles->whereYear('birth_date', (Carbon::today()->year - $request->idade - 1));
                $idade = $request->idade;
            }
            if(!empty($request->genero)){
                if($request->genero == "hm"){
                    $profiles->whereHas('logged_profile', function ($query) {
                        $query->where('gender', 1);
                    });
                    $profiles->where('gender', 0);
                }elseif($request->genero == "hh"){
                    $profiles->whereHas('logged_profile', function ($query) {
                        $query->where('gender', 0);
                    });
                    $profiles->where('gender', 0);
                }elseif($request->genero == "mh"){
                    $profiles->whereHas('logged_profile', function ($query) {
                        $query->where('gender', 0);
                    });
                    $profiles->where('gender', 1);
                }elseif($request->genero == "mm"){
                    $profiles->whereHas('logged_profile', function ($query) {
                        $query->where('gender', 1);
                    });
                    $profiles->where('gender', 1);
                }elseif($request->genero == "outros"){
                    $profiles->whereHas('logged_profile', function ($query) {
                        $query->where('gender', '!=', 0);
                    });
                    $profiles->whereHas('logged_profile', function ($query) {
                        $query->where('gender', '!=', 1);
                    });
                    $profiles->whereNotIn('gender', [0, 1]);
                }
                $genero = $request->genero;
            }
            if(!empty($request->instagram)){
                if($request->instagram == "instagrams"){
                    $profiles->where('instagram', '!=', null);
                }elseif($request->instagram == "instagramn"){
                    $profiles->where('instagram', null);
                }
                $instagram = $request->instagram;
            }
            if(!empty($request->orderby)){
                if($request->orderby == "nomeaz"){
                    $profiles->orderBy('name', 'ASC');
                }elseif($request->orderby == "nomeza"){
                    $profiles->orderBy('name', 'DESC');
                }elseif($request->orderby == "idade01"){
                    $profiles->orderBy('birth_date', 'DESC');
                }elseif($request->orderby == "idade10"){
                    $profiles->orderBy('birth_date', 'ASC');
                }elseif($request->orderby == "distancia01"){
                    $profiles->orderBy('distance_mi', 'ASC');
                }elseif($request->orderby == "distancia10"){
                    $profiles->orderBy('distance_mi', 'DESC');
                }
                $orderby = $request->orderby;
            }else{
                $profiles->orderBy('created_at', 'desc');
            }
            $profiles = $profiles->paginate(24);
            //dd($profiles);
            return view('tinder-tools.index', compact('profiles', 'nome', 'bio', 'idade', 'genero', 'instagram', 'orderby'));
            
        }
    }

    ////TIRAR O PÚBLIC E VER COMO PEGAR O REQUEST->AGENT
    public function get_profile(Request $request){
        $url = 'profile';
        $method = 'GET';
        $body = null;
        $profile = $this->request($url, $method, $body);
        dd($profile);
        /*
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
                    'access_token' => null,
                    'access_token_get_at' => Carbon::parse(Carbon::now())->format('Y-m-d H:i:s') ?? null,
                    'bot' => false,
                    'IP' => \Request::ip() ?? null,
                    'user_agent' => $request->header('User-Agent') ?? null
                ]
            );
            return $profile_id;
        }else{
            return false;
        }
        */
    }

    public function get_updates(){
        $url = 'updates';
        $method = 'POST';
        $body = '{"last_activity_date": "2017-03-25T20:58:00.404Z"}';
        $infos = $this->request($url, $method, $body);
        dd($infos);
    }

    public function get_meta(){
        $url = 'meta';
        $method = 'GET';
        $body = null;
        $infos = $this->request($url, $method, $body);
        dd($infos);
    }
      
    public function like(Request $request, $id){
        $logged_profile_id = $this->get_profile($request);
        $profile = Profile::select('id','tinder_id')->where('tinder_id', $id)->first();
        $url = '/like/'.$profile->tinder_id;
        $method = 'GET';
        $body = null;
        $like = $this->request($url, $method, $body);
        if($like){
            $liked = new Like;
            $liked->logged_profile_id = $logged_profile_id->id;
            $liked->profile_id = $profile->id;
            $liked->save();
            return json_encode(array('success' => true));
        }else{
            return json_encode(array('success' => false));
        }
    }
    /////REVER POR CONTA DO PROFILE LOGGED ID
    public function massive_like(){
        //20/07 09H10MIN
        set_time_limit(7200);//DUAS HORAS
        /*
        $profiles = Profile::select('id','tinder_id','liked','gender')->where('gender',1)->where('liked', null)->limit(100)->get();
        //dd($profiles[0]);
        $likes = 0;
        foreach($profiles as $profile){
            $url = '/like/'.$profile->tinder_id;
            $method = 'GET';
            $body = null;
            $like = $this->request($url, $method, $body);
            if($like){
                $profile->update(['liked' => 1]);
                $likes++;
            }
        }
        return (Carbon::now()." - ".$likes." Likes.");
        */
    }

}

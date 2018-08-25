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
use App\Match;
use App\Super_Like;
use App\Pass;
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
        $super_liked_ids = Super_Like::whereIn('logged_profile_id', $logged_profile_ids)->pluck('profile_id')->all();
        $passed_ids = Pass::whereIn('logged_profile_id', $logged_profile_ids)->pluck('profile_id')->all();
        $not_in_ids = array_unique(array_merge($liked_ids,$super_liked_ids,$passed_ids));
        $profiles = Profile::with('logged_profile:id,lat,lon,birth_date,gender,city')
                        ->whereNotIn('id', $not_in_ids)
                        ->whereIn('logged_profile_id', $logged_profile_ids)
                        ->orderBy('created_at', 'asc')
                        ->paginate(24);
        return view('tinder-tools.index', compact('profiles'));
    }

    public function first_access(Request $request){
        for($i=0;$i<3;$i++){
            $recs = $this->get_recomendations($request->session()->get('tinder-tools')['tinder-tools-id'], $request->session()->get('tinder-tools')['tinder-token']);
            sleep(3);
        }
        return redirect('/tinder-tools');
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
            $logged_profile_ids = Logged_Profile::where('tinder_id', $request->session()->get('tinder-tools')['tinder-id'])->pluck('id')->all();
            
            $liked_ids = Like::whereIn('logged_profile_id', $logged_profile_ids)->pluck('profile_id')->all();
            $super_liked_ids = Super_Like::whereIn('logged_profile_id', $logged_profile_ids)->pluck('profile_id')->all();
            $passed_ids = Pass::whereIn('logged_profile_id', $logged_profile_ids)->pluck('profile_id')->all();
            $not_in_ids = array_unique(array_merge($liked_ids,$super_liked_ids,$passed_ids));

            $profiles = Profile::with('logged_profile:id,lat,lon,gender,birth_date,city')
                            ->whereNotIn('id', $not_in_ids)
                            ->whereIn('logged_profile_id', $logged_profile_ids);
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
                $profiles->orderBy('created_at', 'asc');
            }
            $profiles = $profiles->paginate(24);
            
            return view('tinder-tools.index', compact('profiles', 'nome', 'bio', 'idade', 'genero', 'instagram', 'orderby'));
        }
    }

    public function ajax_recomendations(Request $request){
        $recs = $this->get_recomendations($request->session()->get('tinder-tools')['tinder-tools-id'], $request->session()->get('tinder-tools')['tinder-token']);
        return json_encode($recs);//TALVEZ RETURN BOOLEAN PRA CHECAR SE DEU CERTO
        //IF ERROR RETORNAR ERRO PARA O JQUERY DAR UM ALERT
    }

    function get_recomendations($logged_profile_id, $token){
        $url = 'user/recs';
        $method = 'GET';
        $body = null;
        $recs = $this->request($url, $method, $body, $token);
        $result = [];
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
                $result[] = Profile::updateOrCreate(
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
            return($result);
        }
    }

    public function likes_remaining(Request $request){
        $url = 'meta';
        $method = 'GET';
        $body = null;
        $token = $request->session()->get('tinder-tools')['tinder-token'];
        $infos = $this->request($url, $method, $body, $token);
        if($infos){
            return json_encode($infos);
        }else{
            return ("erro");
        }
    }
    ///////////////////////////////////////////////////////////////////////
    public function get_meta(Request $request){
        $url = 'meta';
        $method = 'GET';
        $body = null;
        $token = $request->session()->get('tinder-tools')['tinder-token'];
        $infos = $this->request($url, $method, $body, $token);
        if($infos){
            return dd($infos);
        }else{
            return ("erro");
        }
    }
    ///////////////////////////////////////////////////////////////////////
    public function like(Request $request, $id){
        $profile = Profile::select('id','tinder_id')->where('tinder_id', $id)->first();
        $logged_profile_id = $request->session()->get('tinder-tools')['tinder-tools-id'];
        $url = '/like/'.$profile->tinder_id;
        $method = 'GET';
        $body = null;
        $token = $request->session()->get('tinder-tools')['tinder-token'];
        $like = $this->request($url, $method, $body, $token);
        if($like){
            $liked = new Like;
            $liked->logged_profile_id = $logged_profile_id;
            $liked->profile_id = $profile->id;
            $liked->save();
            return json_encode(array('success' => true));
        }else{
            return json_encode(array('success' => false));
        }
    }
    /*
    //FUNÇÃO PRA LIKER DIRETO O ID SEM REGISTRAR NO DB - SÓ PARA LIKAR ENTRE OS BOTS
    public function like_id(Request $request, $id){
        $logged_profile_id = $request->session()->get('tinder-tools')['tinder-tools-id'];
        $url = '/like/'.$id;
        $method = 'GET';
        $body = null;
        $token = $request->session()->get('tinder-tools')['tinder-token'];
        $like = $this->request($url, $method, $body, $token);
        if($like){
            return json_encode(array('success' => true));
        }else{
            return json_encode(array('success' => false));
        }
    }
    */
    public function likes(Request $request){
        $logged_profile_ids = Logged_Profile::where('tinder_id', $request->session()->get('tinder-tools')['tinder-id'])->pluck('id')->all();
        $likes = Like::with('logged_profile:id,lat,lon,birth_date,gender,city','profile')
                        ->whereIn('logged_profile_id', $logged_profile_ids)
                        ->orderBy('created_at', 'asc')
                        ->paginate(24);
        return view('tinder-tools.likes', compact('likes'));
    }

    public function super_like(Request $request, $id){
        $profile = Profile::select('id','tinder_id')->where('tinder_id', $id)->first();
        $logged_profile_id = $request->session()->get('tinder-tools')['tinder-tools-id'];
        $url = '/like/'.$profile->tinder_id.'/super';
        $method = 'POST';
        $body = null;
        $token = $request->session()->get('tinder-tools')['tinder-token'];
        $super_like = $this->request($url, $method, $body, $token);
        if($super_like){
            $super_liked = new Super_Like;
            $super_liked->logged_profile_id = $logged_profile_id;
            $super_liked->profile_id = $profile->id;
            $super_liked->save();
            return json_encode(array('success' => true));
        }else{
            return json_encode(array('success' => false));
        }
    }

    public function super_likes(Request $request){
        $logged_profile_ids = Logged_Profile::where('tinder_id', $request->session()->get('tinder-tools')['tinder-id'])->pluck('id')->all();
        $super_likes = Super_Like::with('logged_profile:id,lat,lon,birth_date,gender,city','profile')
                        ->whereIn('logged_profile_id', $logged_profile_ids)
                        ->orderBy('created_at', 'asc')
                        ->paginate(24);
        return view('tinder-tools.super_likes', compact('likes'));
    }

    public function pass(Request $request, $id){
        $profile = Profile::select('id','tinder_id')->where('tinder_id', $id)->first();
        $logged_profile_id = $request->session()->get('tinder-tools')['tinder-tools-id'];
        $url = '/pass/'.$profile->tinder_id;
        $method = 'GET';
        $body = null;
        $token = $request->session()->get('tinder-tools')['tinder-token'];
        $pass = $this->request($url, $method, $body, $token);
        if($pass){
            $passed = new Pass;
            $passed->logged_profile_id = $logged_profile_id;
            $passed->profile_id = $profile->id;
            $passed->save();
            return json_encode(array('success' => true));
        }else{
            return json_encode(array('success' => false));
        }
    }

    public function get_profile($profile_id, $logged_profile_id, $token){
        $url = 'user/'.$profile_id;
        $method = 'GET';
        $body = null;
        $profile = $this->request($url, $method, $body, $token);
        if($profile){
            $photos = [];
            $spotify = [];
            foreach($profile->results->photos as $photo){
                $photos[] = $photo->url;
            }
            if(isset($profile->results->spotify_theme_track->artists)){
                foreach($profile->results->spotify_theme_track->artists as $artist){
                    $spotify[] = $artist;
                }
            }
            $profile_db = Profile::updateOrCreate(
                ['tinder_id' => $profile->results->_id],
                [
                    'logged_profile_id' => $logged_profile_id ?? null,
                    'tinder_id' => $profile->results->_id ?? null,
                    'group_matched' => $profile->results->group_matched ?? null,
                    'distance_mi' => $profile->results->distance_mi ?? null,
                    'content_hash' => $profile->results->content_hash ?? null,
                    'common_friends' => json_encode($profile->results->common_friends ?? null),
                    'common_likes' => json_encode($profile->results->common_likes ?? null),
                    'common_friend_count' => $profile->results->common_friend_count ?? null,
                    'common_like_count' => $profile->results->common_like_count ?? null,
                    'connection_count' => $profile->results->connection_count ?? null,
                    'bio' => $profile->results->bio ?? null,
                    'birth_date' => Carbon::parse($profile->results->birth_date)->format('Y-m-d H:i:s') ?? null,
                    'name' => $profile->results->name ?? null,
                    'ping_time' => Carbon::parse($profile->results->ping_time)->format('Y-m-d H:i:s') ?? null,
                    'photos' => json_encode($photos) ?? null,
                    'instagram' => $profile->instagram->results->username ?? null,
                    'spotify' => json_encode($spotify) ?? null,
                    'jobs' => json_encode($profile->results->jobs ?? null),
                    'schools' => json_encode($profile->results->schools ?? null),
                    'teasers' => json_encode($profile->results->teasers ?? null),
                    'gender' => $profile->results->gender ?? null,
                    'birth_date_info' => $profile->results->birth_date_info ?? null,
                    's_number' => $profile->results->s_number ?? null
                ]
            );
            return($profile_db->id);
        }else{
            return false;
        }
    }

    public function matches(Request $request){
        //O PAGINATION VAI FICAR PEGANDO OS MATCHES ***
        $get_matches = $this->get_matches($request->session()->get('tinder-tools')['tinder-tools-id'], $request->session()->get('tinder-tools')['tinder-token']);
        if($get_matches){
            //PEGAR MENSAGENS E RETORNAR TBMMMM
            $logged_profile_ids = Logged_Profile::where('tinder_id', $request->session()->get('tinder-tools')['tinder-id'])->pluck('id')->all();
            $matches = Match::with('logged_profile','profile')
                            ->whereIn('logged_profile_id', $logged_profile_ids)
                            ->orderBy('created_at', 'desc')
                            ->paginate(24);
            return view('tinder-tools.matches', compact('matches'));
        }else{
            return view('tinder-tools.index')->withErrors(['Ocorreu um erro ao processar seus Matches. Tente novamente mais tarde.']);
        }
    }

    function get_matches($logged_profile_id, $token){
        $url = 'updates';
        $method = 'POST';
        $body = '{"last_activity_date": ""}';
        $matches = $this->request($url, $method, $body, $token);
        //dd($matches);
        if($matches){
            foreach($matches->matches as $match){
                $profile_id = $this->get_profile($match->person->_id, $logged_profile_id, $token);
                Match::updateOrCreate(
                    ['match_id' => $match->id],
                    [
                        'match_id' => $match->id ?? null,
                        'logged_profile_id' => $logged_profile_id ?? null,
                        'profile_id' => $profile_id ?? null,
                        'closed' => $match->closed ?? null,
                        'common_friend_count' => $match->common_friend_count ?? null,
                        'common_like_count' => $match->common_like_count ?? null,
                        'created_date' => Carbon::parse($match->created_date)->format('Y-m-d H:i:s') ?? null,
                        'dead' => $match->dead ?? null,
                        'last_activity_date' => Carbon::parse($match->last_activity_date)->format('Y-m-d H:i:s') ?? null,
                        'message_count' => $match->message_count ?? null,
                        'muted' => $match->muted ?? null,
                        'participants' => json_encode($match->participants) ?? null,
                        'pending' => $match->pending ?? null,
                        'is_super_like' => $match->is_super_like ?? null,
                        'is_boost_match' => $match->is_boost_match ?? null,
                        'is_fast_match' => $match->is_fast_match ?? null,
                        'following' => $match->following ?? null,
                        'following_moments' => $match->following_moments ?? null,
                        'created_at' => Carbon::now() ?? null,
                    ]
                );
            }
            return true;
        }else{
            return false;
        }
    }



    //FUNÇÕES ABAIXO FALTA TRATAR

    public function get_updates(){
        $url = 'updates';
        $method = 'POST';
        $body = '{"last_activity_date": "2018-01-01T00:00:00.000Z"}';
        $token = "14b900f3-7ed9-497d-8e27-83c915ab71dd";
        $infos = $this->request($url, $method, $body, $token);
        dd($infos);
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

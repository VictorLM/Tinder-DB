<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Profile;
use App\Location;
use Validator;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    //PRINTAR MAIS INFORMAÇÕES
    //CRIAR BOTÕES COM AÇÕES IF LIKED
    public function index(){
        $profiles = Profile::with('search_location:id,lat,lon')
            ->orderBy('created_at', 'desc')
            ->paginate(21);
        //dd($profiles[0]);
        return view('index', compact('profiles'));
    }

    public function search(Request $request){
        $validatedData = Validator::make($request->all(), [
            'nome' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:50',
            'idade' => 'nullable|integer|between:18,99',
            'genero' => [
                'nullable',
                Rule::in(['masculino', 'feminino', 'outros']),
            ],
            'distancia' => 'nullable|integer|between:0,999',
            'orderby' => [
                'nullable',
                Rule::in(['nomeaz', 'nomeza', 'idade01', 'idade10', 'distancia01', 'distancia10']),
            ]
        ]);

        if ($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput();
        }else{
            $nome = null;
            $bio = null;
            $idade = null;
            $genero = null;
            $distancia = null;
            $orderby = null;
            $profiles = $profiles = Profile::with('search_location:id,lat,lon');
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
                if($request->genero == "masculino"){
                    $profiles->where('gender', 0);
                }elseif($request->genero == "feminino"){
                    $profiles->where('gender', 1);
                }elseif($request->genero == "outros"){
                    $profiles->whereNotIn('gender', [0, 1]);
                }
                $genero = $request->genero;
            }
            if(!empty($request->distancia)){
                //AJUSTAR AQUI QUANDO MOSTRAR SEARCH_LOCATION
                $profiles->where('distance_mi', '<=', round(($request->distancia * 1.60934), 0));
                $distancia = $request->distancia;
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
            $profiles = $profiles->paginate(21);
            return view('index', compact('profiles', 'nome', 'bio', 'idade', 'genero', 'distancia', 'orderby'));
        }
    }

    public function get_recomendations(){
        $url = 'user/recs';
        $method = 'GET';
        $body = null;
        $recs = $this->request($url, $method, $body);
        //dd($recs);
        if($recs){
            $location_id = $this->get_profile();
            foreach($recs->results as $rec){
                Profile::updateOrCreate(
                    ['tinder_id' => $rec->_id],
                    [
                        'search_location_id' => $location_id ?? null,
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
                        'photos' => json_encode($rec->photos ?? null),
                        'instagram' => json_encode($rec->instagram ?? null),
                        'jobs' => json_encode($rec->jobs ?? null),
                        'schools' => json_encode($rec->schools ?? null),
                        'teaser' => json_encode($rec->teaser ?? null),
                        'teasers' => json_encode($rec->teasers ?? null),
                        'gender' => $rec->gender ?? null,
                        'birth_date_info' => $rec->birth_date_info ?? null,
                        's_number' => $rec->s_number ?? null
                    ]
                );
            }
        }
    }

    public function like(){
        //19/07 17H
        set_time_limit(7200);//DUAS HORAS
        $profiles = Profile::select('id','tinder_id','liked','gender')->where('gender',1)->where('liked',0)->limit(100)->get();
        //dd($profiles[0]);
        foreach($profiles as $profile){
            $url = '/like/'.$profile->tinder_id;
            $method = 'GET';
            $body = null;
            $like = $this->request($url, $method, $body);
            if($like){
                $profile->update(['liked' => 1]);
            }
        }
    }

    public function get_profile(){
        $url = 'profile';
        $method = 'GET';
        $body = null;
        $infos = $this->request($url, $method, $body);
        //dd($infos);
        if($infos){
            $id = Location::updateOrCreate(
                ['at' => $infos->pos->at],
                [
                    'at' => $infos->pos->at ?? null,
                    'lat' => $infos->pos->lat ?? null,
                    'lon' => $infos->pos->lon ?? null,
                    'city' => $infos->pos_info->city->name ?? null,
                    'country' => $infos->pos_info->country->name ?? null,
                    'pos' => json_encode($infos->pos ?? null),
                    'pos_info' => json_encode($infos->pos_info ?? null),
                    'complete_profile' => json_encode($infos ?? null)
                ]
            );
            return $id->id;
        }
    }

    function request($url, $method, $body){
        $tinder_token = DB::table('tokens')->where('name', 'Tinder Access Token')->value('value');
        $client = new Client();
        $headers = [
            'app_version'   => '6.9.4',
            'platform'      => 'ios',
            'content-type'  => 'application/json',
            'User-agent'    => 'Tinder/7.5.3 (iPhone; iOS 10.3.2; Scale/2.00)',
            'Accept'        => 'application/json',
            'X-Auth-Token'  => $tinder_token,
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

}
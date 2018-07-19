<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Profile;
use App\Location;

class ClientController extends Controller
{
    
    public function index(){
        $profiles = DB::table('profiles')->get();
        return view('index', compact('profiles'));
    }

    public function get_recomendations(){

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

        try {
            $recs = $client->request('GET', 'https://api.gotinder.com/user/recs', [
                'headers' => $headers
            ]);
            if($recs->getStatusCode() == 200){
                $location_id = $this->get_current_location();
                $recs = json_decode($recs->getBody());
                
                foreach($recs->results as $rec){
                    Profile::updateOrCreate(
                        ['tinder_id' => $rec->_id],
                        [
                            'search_location_id' => $location_id?? null,
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
            
        } catch (Exception $e) {
            report($e);
            return false;
        }
        
    }

    public function get_current_location(){

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

        try {
            $infos = $client->request('GET', 'https://api.gotinder.com/profile', [
                'headers' => $headers
            ]);
            if($infos->getStatusCode() == 200){

                $infos = json_decode($infos->getBody());

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
                //dd($id->id);
                return $id->id;
            }
            
        } catch (Exception $e) {
            report($e);
            return false;
        }
        
    }


}

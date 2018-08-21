<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Profile;
use App\Logged_Profile;

class GetTinderRecs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GetTinderRecs:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pega as recomendações de like do tinder dos perfis dos bots.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    function request($url, $method, $token){
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
                'headers' => $headers
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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bots = Logged_Profile::select('id', 'access_token', 'access_token_get_at')->where('bot', true)->get();
        foreach($bots as $bot){
            if(Carbon::parse($bot->access_token_get_at)->diffInHours(Carbon::now()) < 24){
                $url = 'user/recs';
                $method = 'GET';
                $token = $bot->access_token;
                $recs = $this->request($url, $method, $token);
                if($recs){
                    foreach($recs->results as $rec){
                        $photos = [];
                        $spotify = []; //https://open.spotify.com/artist/
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
                                'logged_profile_id' => $bot->id ?? null,
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
        }
    }

}
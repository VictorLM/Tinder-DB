<?php

namespace App\Http\Middleware;

use Closure;
use App\Logged_Profile;
use Carbon\Carbon;

class token
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if($request->session()->exists('tinder-tools') && Carbon::parse($request->session()->get('tinder-tools')['access-token-get-at'])->diffInHours(Carbon::now()) < 12){
            //dd($request->session()->get('tinder-tools')['access-token-get-at']);
            $logged_profile = Logged_Profile::find($request->session()->get('tinder-tools')['tinder-tools-id']);
            if($logged_profile->count()>0){
                return $next($request);
            }else{
                //COLOCAR MENSAGEM NA SESSÃO
                return redirect('/tinder-tools/login');
            }
        }else{
            //COLOCAR MENSAGEM NA SESSÃO
            return redirect('/tinder-tools/login');
        }
        //return $next($request);
    }
}

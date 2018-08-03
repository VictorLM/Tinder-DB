<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use App\Logged_Profile;

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
        //dd($request->session());
        if($request->session()->exists('tinder-tools-id') && $request->session()->exists('tinder-id') && $request->session()->exists('tinder-token') && $request->session()->exists('access-token-get-at')){
            $logged_profile = Logged_Profile::find($request->session()->get('tinder-tools-id'));
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

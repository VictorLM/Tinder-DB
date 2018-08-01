<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
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
        if($request->session()->exists('tinder-tools-id')){
            $logged_profile = Logged_Profile::find($request->session()->exists('tinder-tools-id'));
            if($logged_profile->count()>0){
                return redirect('/tinder-tools/recs');
            }else{
                //COLOCAR MENSAGEM NA SESSÃO
                return redirect('tinder-tools/login');
            }
        }else{
            //COLOCAR MENSAGEM NA SESSÃO
            return redirect('tinder-tools/login');
        }
        return $next($request);
    }
}

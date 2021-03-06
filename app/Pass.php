<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pass extends Model
{
    protected $table = 'passes';
    protected $guarded = [];

    public function logged_profile(){
        return $this->belongsTo('App\Logged_Profile', 'logged_profile_id');
    }
    public function profile(){
        return $this->belongsTo('App\Profile', 'profile_id');
    }
}

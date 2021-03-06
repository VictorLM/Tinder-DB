<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model{
    protected $table = 'profiles';
    protected $guarded = [];

    public function logged_profile(){
        return $this->belongsTo('App\Logged_Profile', 'logged_profile_id');
    }

}

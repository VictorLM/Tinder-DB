<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model{
    protected $table = 'profiles';
    protected $guarded = [];

    public function search_location(){
        return $this->belongsTo('App\Location', 'search_location_id');
    }

}

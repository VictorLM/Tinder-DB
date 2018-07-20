<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class Tokens extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tokens')->insert([
            'name' => 'Tinder Access Token',
            'value' => null,
            'created_at' => Carbon::now()
        ]);

        DB::table('tokens')->insert([
            'name' => 'Facebook Access Token',
            'value' => null,
            'created_at' => null
        ]);

        DB::table('tokens')->insert([
            'name' => 'Facebook ID',
            'value' => '100026580252906',
            'created_at' => Carbon::now()
        ]);
    }
}

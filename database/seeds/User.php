<?php

use App\User as ModelUser;
use Illuminate\Database\Seeder;

class User extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // This was included mainly to help undestand why the Oauth2 delegation protocol is used,
        // our api end users are applications.
        factory(ModelUser::class)->create([
            'name' => 'Mobile App Client'
        ]);
        factory(ModelUser::class)->create([
            'name' => 'Browser Minesweeper'
        ]);
    }
}

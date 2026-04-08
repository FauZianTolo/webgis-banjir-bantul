<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // //create a new user
        // $user = new \App\Models\User();
        // $user->name = 'Tolo';
        // $user->phone = '087834755177';
        // $user->email = 'ziantolo41-@gmail.com';
        // $user->password = bcrypt('admin1234');
        // $user->save();

        //create multiple users
        $user = [
            [
                'name' => 'Tolo',
                'phone' => '087834755177',
                'email' => 'ziantolo410@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'name' => 'Fahmi',
                'phone' => '08123456789',
                'email' => 'amirulfahmi@gmail.com',
                'password' => bcrypt('123456'),
            ],
        ];

        //insert the user into the database
        DB::table('users')->insert($user);
    }
}

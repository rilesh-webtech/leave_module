<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use Faker\Factory as Faker;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach(range(1, 15) as $index) {
            $int= mt_rand(1641014400,1664601600);
            \App\Models\User::create([
                 'email' => $faker->email(),
                 'name' => $faker->sentence(5),
                 'email_verified_at' => date("Y-m-d H:i:s",$int),
                 'joined_at'=> date("Y-m-d H:i:s",$int),
                 'password' => Hash::make('12345678'),
            ]);
        }
    }
}

<?php

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) { // Adjust the loop count as needed
            DB::table('users')->insert([
                'id' => $faker->uuid,
                'username' => $faker->userName,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $faker->phoneNumber,
                'active' => $faker->boolean,
                'otp_token' => null,
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Change 'password' to whatever default password you want
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

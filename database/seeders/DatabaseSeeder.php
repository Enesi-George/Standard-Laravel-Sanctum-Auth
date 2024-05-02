<?php

namespace Database\Seeders;

use App\Models\Team;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $teams = Team::all();

        User::factory(2)
            ->active()
            ->create()
            ->each(function ($user) use ($teams) {
                $randomTeam = $teams->random();
                $user->teams()->attach($randomTeam);
            });
    }
}

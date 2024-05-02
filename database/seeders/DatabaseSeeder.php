<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        try {
            $teams = Team::all();

            if ($teams->isEmpty()) {
                throw new \Exception("No teams found. Please check the RolesAndPermissionsSeeder to create teams before seeding users.");
            }

            User::factory(2)
                ->active()
                ->create()
                ->each(function ($user) use ($teams) {
                    $randomTeam = $teams->random();
                    $user->teams()->attach($randomTeam);
                });
        } catch (ModelNotFoundException $e) {
            // Handle the case where the Team model is not found
            // This could happen if the Team model is not properly configured or does not exist
            // Log the error or handle it according to your application's needs
            // For now, we're just rethrowing the exception
            throw $e;
        } catch (\Exception $e) {
            // Handle other exceptions
            // For now, we're just rethrowing the exception
            throw $e;
        }
    }
}

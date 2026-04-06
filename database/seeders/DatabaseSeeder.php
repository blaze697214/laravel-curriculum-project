<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $cdc = User::factory()->create([
            'name' => 'blaze',
            'email' => 'cdc@gmail.com',
            'password' => Hash::make('cdc123'),
        ]);

        $cdcRole = Role::create(['name' => 'cdc']);
        // $cdc_deptRole = Role::create(['name' => 'cdc-dept']);
        $hodRole = Role::create(['name' => 'hod']);
        // $moderatorRole = Role::create(['name' => 'moderator']);
        $expertRole = Role::create(['name' => 'expert']);

        $cdc->roles()->attach($cdcRole->id);
    }
}

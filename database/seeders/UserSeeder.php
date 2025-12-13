<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Acme Corporation Users
        $acme = Organization::where('slug', 'acme-corp')->first();
        
        User::create([
            'org_id' => $acme->id,
            'name' => 'John Admin (Acme)',
            'email' => 'john.admin@acme-corp.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'org_id' => $acme->id,
            'name' => 'Sarah Employee (Acme)',
            'email' => 'sarah@acme-corp.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        User::create([
            'org_id' => $acme->id,
            'name' => 'Mike Employee (Acme)',
            'email' => 'mike@acme-corp.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        User::create([
            'org_id' => $acme->id,
            'name' => 'Emily Employee (Acme)',
            'email' => 'emily@acme-corp.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        // TechStart Inc Users
        $techstart = Organization::where('slug', 'techstart-inc')->first();
        
        User::create([
            'org_id' => $techstart->id,
            'name' => 'Alice Admin (TechStart)',
            'email' => 'alice.admin@techstart-inc.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'org_id' => $techstart->id,
            'name' => 'Bob Employee (TechStart)',
            'email' => 'bob@techstart-inc.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        User::create([
            'org_id' => $techstart->id,
            'name' => 'Carol Employee (TechStart)',
            'email' => 'carol@techstart-inc.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        User::create([
            'org_id' => $techstart->id,
            'name' => 'David Employee (TechStart)',
            'email' => 'david@techstart-inc.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        // Global Systems Ltd Users
        $global = Organization::where('slug', 'global-systems')->first();
        
        User::create([
            'org_id' => $global->id,
            'name' => 'Frank Admin (Global)',
            'email' => 'frank.admin@global-systems.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'org_id' => $global->id,
            'name' => 'Grace Employee (Global)',
            'email' => 'grace@global-systems.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        User::create([
            'org_id' => $global->id,
            'name' => 'Henry Employee (Global)',
            'email' => 'henry@global-systems.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        User::create([
            'org_id' => $global->id,
            'name' => 'Iris Employee (Global)',
            'email' => 'iris@global-systems.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'status' => 'active',
        ]);
    }
}

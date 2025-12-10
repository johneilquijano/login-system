<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create organizations
        $orgs = [
            [
                'name' => 'Acme Corporation',
                'slug' => 'acme-corp',
                'email' => 'contact@acmecorp.local',
                'description' => 'Leading technology solutions provider',
                'phone' => '+1 (555) 123-4567',
                'address' => '123 Tech Street',
                'city' => 'San Francisco',
                'state' => 'CA',
                'postal_code' => '94105',
                'country' => 'USA',
            ],
            [
                'name' => 'Global Enterprises',
                'slug' => 'global-enterprises',
                'email' => 'contact@globalenterprises.local',
                'description' => 'International business solutions',
                'phone' => '+1 (555) 987-6543',
                'address' => '456 Business Ave',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country' => 'USA',
            ],
        ];

        foreach ($orgs as $org) {
            Organization::create($org);
        }

        // Create users for Acme Corporation
        $acme = Organization::where('slug', 'acme-corp')->first();

        // Admin user
        User::create([
            'org_id' => $acme->id,
            'first_name' => 'John',
            'last_name' => 'Admin',
            'email' => 'admin@acmecorp.local',
            'password' => Hash::make('Password123'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Employee users
        $employees = [
            ['first_name' => 'Jane', 'last_name' => 'Employee', 'email' => 'jane@acmecorp.local'],
            ['first_name' => 'Bob', 'last_name' => 'Smith', 'email' => 'bob@acmecorp.local'],
            ['first_name' => 'Alice', 'last_name' => 'Johnson', 'email' => 'alice@acmecorp.local'],
            ['first_name' => 'Charlie', 'last_name' => 'Brown', 'email' => 'charlie@acmecorp.local'],
            ['first_name' => 'Diana', 'last_name' => 'Prince', 'email' => 'diana@acmecorp.local'],
        ];

        foreach ($employees as $emp) {
            User::create([
                'org_id' => $acme->id,
                'first_name' => $emp['first_name'],
                'last_name' => $emp['last_name'],
                'email' => $emp['email'],
                'password' => Hash::make('Password123'),
                'role' => 'employee',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
        }

        // Create users for Global Enterprises
        $global = Organization::where('slug', 'global-enterprises')->first();

        User::create([
            'org_id' => $global->id,
            'first_name' => 'Sarah',
            'last_name' => 'Admin',
            'email' => 'admin@globalenterprises.local',
            'password' => Hash::make('Password123'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'org_id' => $global->id,
            'first_name' => 'Tom',
            'last_name' => 'Employee',
            'email' => 'tom@globalenterprises.local',
            'password' => Hash::make('Password123'),
            'role' => 'employee',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}

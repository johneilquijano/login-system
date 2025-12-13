<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organization::create([
            'name' => 'Acme Corporation',
            'slug' => 'acme-corp',
            'email' => 'admin@acme-corp.com',
            'description' => 'A leading innovator in technology solutions',
            'phone' => '+1-555-0101',
            'address' => '123 Tech Street',
            'city' => 'San Francisco',
            'state' => 'CA',
            'postal_code' => '94105',
            'country' => 'USA',
            'status' => 'active',
        ]);

        Organization::create([
            'name' => 'TechStart Inc',
            'slug' => 'techstart-inc',
            'email' => 'admin@techstart-inc.com',
            'description' => 'Innovative solutions for modern businesses',
            'phone' => '+1-555-0102',
            'address' => '456 Innovation Ave',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'USA',
            'status' => 'active',
        ]);

        Organization::create([
            'name' => 'Global Systems Ltd',
            'slug' => 'global-systems',
            'email' => 'admin@global-systems.com',
            'description' => 'Providing comprehensive enterprise solutions worldwide',
            'phone' => '+1-555-0103',
            'address' => '789 Global Boulevard',
            'city' => 'Chicago',
            'state' => 'IL',
            'postal_code' => '60601',
            'country' => 'USA',
            'status' => 'active',
        ]);
    }
}

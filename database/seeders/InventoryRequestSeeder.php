<?php

namespace Database\Seeders;

use App\Models\InventoryRequest;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class InventoryRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample inventory requests for each organization
        $acme = Organization::where('slug', 'acme-corp')->first();
        $techstart = Organization::where('slug', 'techstart-inc')->first();
        $global = Organization::where('slug', 'global-systems')->first();

        $items = ['Office Supplies', 'Printer Paper', 'Desk Lamp', 'Monitor Stand'];

        // Acme Inventory Requests
        $acmeUsers = User::where('org_id', $acme->id)->where('role', 'employee')->get();
        foreach ($acmeUsers as $index => $user) {
            InventoryRequest::create([
                'org_id' => $acme->id,
                'user_id' => $user->id,
                'item_name' => $items[$index % count($items)],
                'description' => 'Inventory request for ' . $items[$index % count($items)],
                'quantity_requested' => rand(1, 5),
                'reason' => 'Office supplies needed',
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);
        }

        // TechStart Inventory Requests
        $techstartUsers = User::where('org_id', $techstart->id)->where('role', 'employee')->get();
        foreach ($techstartUsers as $index => $user) {
            InventoryRequest::create([
                'org_id' => $techstart->id,
                'user_id' => $user->id,
                'item_name' => $items[$index % count($items)],
                'description' => 'Inventory request for ' . $items[$index % count($items)],
                'quantity_requested' => rand(1, 5),
                'reason' => 'Replenish stock',
                'status' => 'approved',
                'submitted_at' => now()->subDays(3),
                'approved_at' => now()->subDays(2),
            ]);
        }

        // Global Inventory Requests
        $globalUsers = User::where('org_id', $global->id)->where('role', 'employee')->get();
        foreach ($globalUsers as $index => $user) {
            InventoryRequest::create([
                'org_id' => $global->id,
                'user_id' => $user->id,
                'item_name' => $items[$index % count($items)],
                'description' => 'Inventory request for ' . $items[$index % count($items)],
                'quantity_requested' => rand(1, 5),
                'reason' => 'Maintenance supplies',
                'status' => 'fulfilled',
                'submitted_at' => now()->subDays(7),
                'approved_at' => now()->subDays(6),
                'fulfilled_at' => now()->subDays(5),
            ]);
        }
    }
}

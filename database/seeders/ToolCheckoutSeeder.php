<?php

namespace Database\Seeders;

use App\Models\ToolCheckout;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class ToolCheckoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample tool checkouts for each organization
        $acme = Organization::where('slug', 'acme-corp')->first();
        $techstart = Organization::where('slug', 'techstart-inc')->first();
        $global = Organization::where('slug', 'global-systems')->first();

        $tools = ['Laptop', 'Projector', 'Wireless Mouse', 'External Hard Drive'];

        // Acme Tool Checkouts
        $acmeUsers = User::where('org_id', $acme->id)->where('role', 'employee')->get();
        foreach ($acmeUsers as $index => $user) {
            ToolCheckout::create([
                'org_id' => $acme->id,
                'user_id' => $user->id,
                'tool_name' => $tools[$index % count($tools)],
                'description' => 'Tool checkout request',
                'serial_number' => 'SN-' . str_pad($acme->id . $user->id, 6, '0', STR_PAD_LEFT),
                'status' => 'requested',
                'requested_at' => now(),
            ]);
        }

        // TechStart Tool Checkouts
        $techstartUsers = User::where('org_id', $techstart->id)->where('role', 'employee')->get();
        foreach ($techstartUsers as $index => $user) {
            ToolCheckout::create([
                'org_id' => $techstart->id,
                'user_id' => $user->id,
                'tool_name' => $tools[$index % count($tools)],
                'description' => 'Tool checkout request',
                'serial_number' => 'SN-' . str_pad($techstart->id . $user->id, 6, '0', STR_PAD_LEFT),
                'status' => 'checked_out',
                'requested_at' => now()->subDays(5),
                'approved_at' => now()->subDays(4),
                'checked_out_at' => now()->subDays(3),
                'return_due_date' => now()->addDays(7),
            ]);
        }

        // Global Tool Checkouts
        $globalUsers = User::where('org_id', $global->id)->where('role', 'employee')->get();
        foreach ($globalUsers as $index => $user) {
            ToolCheckout::create([
                'org_id' => $global->id,
                'user_id' => $user->id,
                'tool_name' => $tools[$index % count($tools)],
                'description' => 'Tool checkout request',
                'serial_number' => 'SN-' . str_pad($global->id . $user->id, 6, '0', STR_PAD_LEFT),
                'status' => 'approved',
                'requested_at' => now()->subDays(10),
                'approved_at' => now()->subDays(9),
            ]);
        }
    }
}

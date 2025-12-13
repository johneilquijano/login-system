<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample documents for each organization
        $acme = Organization::where('slug', 'acme-corp')->first();
        $techstart = Organization::where('slug', 'techstart-inc')->first();
        $global = Organization::where('slug', 'global-systems')->first();

        // Acme Documents
        $acmeUsers = User::where('org_id', $acme->id)->where('role', 'employee')->get();
        foreach ($acmeUsers as $user) {
            Document::create([
                'org_id' => $acme->id,
                'user_id' => $user->id,
                'title' => "Employment Contract - {$user->name}",
                'description' => 'Standard employment agreement',
                'file_path' => 'documents/contract_' . $user->id . '.pdf',
                'file_name' => 'employment_contract.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 125000,
                'status' => 'pending_review',
            ]);
        }

        // TechStart Documents
        $techstartUsers = User::where('org_id', $techstart->id)->where('role', 'employee')->get();
        foreach ($techstartUsers as $user) {
            Document::create([
                'org_id' => $techstart->id,
                'user_id' => $user->id,
                'title' => "NDA Agreement - {$user->name}",
                'description' => 'Non-disclosure agreement',
                'file_path' => 'documents/nda_' . $user->id . '.pdf',
                'file_name' => 'nda_agreement.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 85000,
                'status' => 'approved',
                'signed_at' => now(),
            ]);
        }

        // Global Documents
        $globalUsers = User::where('org_id', $global->id)->where('role', 'employee')->get();
        foreach ($globalUsers as $user) {
            Document::create([
                'org_id' => $global->id,
                'user_id' => $user->id,
                'title' => "Policy Document - {$user->name}",
                'description' => 'Company policies and procedures',
                'file_path' => 'documents/policy_' . $user->id . '.pdf',
                'file_name' => 'company_policies.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 250000,
                'status' => 'draft',
            ]);
        }
    }
}

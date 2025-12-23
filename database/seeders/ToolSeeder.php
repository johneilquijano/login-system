<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first organization for demo
        $org = Organization::first();

        if (!$org) {
            $this->command->info('No organizations found. Please create an organization first.');
            return;
        }

        $tools = [
            [
                'name' => 'Power Drill',
                'category' => 'Power Tools',
                'condition' => 'good',
                'description' => 'Cordless power drill for general use',
                'serial_number' => 'PD-2024-001',
                'image_path' => $this->downloadPlaceholderImage('power-drill.jpg', '1f4e0'),
            ],
            [
                'name' => 'Circular Saw',
                'category' => 'Power Tools',
                'condition' => 'good',
                'description' => 'High-performance circular saw',
                'serial_number' => 'CS-2024-001',
                'image_path' => $this->downloadPlaceholderImage('circular-saw.jpg', '26a1'),
            ],
            [
                'name' => 'Tool Set',
                'category' => 'Hand Tools',
                'condition' => 'good',
                'description' => 'Complete set of hand tools including wrenches and screwdrivers',
                'serial_number' => 'TS-2024-001',
                'image_path' => $this->downloadPlaceholderImage('tool-set.jpg', '1f527'),
            ],
            [
                'name' => 'Ladder',
                'category' => 'Ladders',
                'condition' => 'fair',
                'description' => '6-foot aluminum ladder',
                'serial_number' => 'LD-2024-001',
                'image_path' => $this->downloadPlaceholderImage('ladder.jpg', '1f3d7'),
            ],
            [
                'name' => 'Socket Set',
                'category' => 'Hand Tools',
                'condition' => 'good',
                'description' => 'SAE and metric socket set',
                'serial_number' => 'SS-2024-001',
                'image_path' => $this->downloadPlaceholderImage('socket-set.jpg', '1f527'),
            ],
            [
                'name' => 'Grinding Machine',
                'category' => 'Power Tools',
                'condition' => 'needs_repair',
                'description' => 'Angle grinder for metal work',
                'serial_number' => 'GM-2024-001',
                'image_path' => $this->downloadPlaceholderImage('grinder.jpg', '26a1'),
            ],
            [
                'name' => 'Hammer Set',
                'category' => 'Hand Tools',
                'condition' => 'good',
                'description' => 'Set of hammers in various sizes',
                'serial_number' => 'HS-2024-001',
                'image_path' => $this->downloadPlaceholderImage('hammer.jpg', '1f528'),
            ],
            [
                'name' => 'Drill Bits Set',
                'category' => 'Accessories',
                'condition' => 'good',
                'description' => 'Complete set of drill bits for various materials',
                'serial_number' => 'DB-2024-001',
                'image_path' => $this->downloadPlaceholderImage('drill-bits.jpg', '1f4a1'),
            ],
        ];

        foreach ($tools as $tool) {
            Tool::updateOrCreate(
                ['serial_number' => $tool['serial_number'], 'org_id' => $org->id],
                array_merge($tool, ['org_id' => $org->id])
            );
        }

        $this->command->info('Tool seeder completed successfully!');
    }

    private function downloadPlaceholderImage(string $filename, string $emoji = '1f4e0'): ?string
    {
        // Use public disk for accessible images
        $publicPath = public_path('storage/tools');
        
        if (!is_dir($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        $filepath = $publicPath . '/' . $filename;

        // Use ui-avatars service for nice placeholder images
        $url = "https://ui-avatars.com/api/?name=" . urlencode(str_replace(['.jpg', '.png'], '', $filename)) . "&size=400&background=random&bold=true&rounded=true";
        
        $imageContent = @file_get_contents($url);
        
        if ($imageContent !== false && strlen($imageContent) > 100) {
            file_put_contents($filepath, $imageContent);
            return 'storage/tools/' . $filename;
        }

        // Fallback: return null if download fails
        return null;
    }
}

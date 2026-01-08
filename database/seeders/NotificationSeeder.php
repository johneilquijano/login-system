<?php

namespace Database\Seeders;

use App\Models\AppNotification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test notifications for the first user
        $user = User::first();
        
        if ($user) {
            AppNotification::createNotification(
                user: $user,
                type: 'test.notification',
                title: 'Welcome to Notifications',
                message: 'Your notification system is working! This is a test message.',
                linkUrl: route('notifications.index'),
                data: ['test' => true]
            );

            AppNotification::createNotification(
                user: $user,
                type: 'documents.assigned',
                title: 'New Document Assigned',
                message: 'Document "Employee Handbook" has been assigned to you for review.',
                linkUrl: route('dashboard'),
                data: ['document_id' => 1]
            );

            AppNotification::createNotification(
                user: $user,
                type: 'inventory_requests.approved',
                title: 'Request Approved',
                message: 'Your inventory request "Office Supplies" has been approved.',
                linkUrl: route('dashboard'),
                data: ['request_id' => 1]
            );

            echo "✅ Test notifications created successfully!\n";
        } else {
            echo "❌ No users found. Create a user first.\n";
        }
    }
}

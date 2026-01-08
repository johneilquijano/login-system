<?php

namespace App\Listeners;

use App\Models\AppNotification;
use App\Models\User;

class NotifyDocumentAssigned
{
    /**
     * Handle document assigned event (to be fired from DocumentController)
     */
    public function handle($event): void
    {
        $document = $event->document;

        // Notify the employee
        AppNotification::createNotification(
            user: $document->user,
            type: 'documents.assigned',
            title: 'New Document Assigned',
            message: 'A new document "' . $document->title . '" has been assigned to you for review.',
            linkUrl: route('documents.show', $document),
            data: ['document_id' => $document->id]
        );

        // Also notify all admins in the organization
        $admins = User::forOrganization($document->org_id)
            ->where('role', 'admin')
            ->get();

        foreach ($admins as $admin) {
            AppNotification::createNotification(
                user: $admin,
                type: 'documents.assigned',
                title: 'Document Assigned to Employee',
                message: 'Document "' . $document->title . '" assigned to ' . $document->user->name . ' for review.',
                linkUrl: route('admin.documents.show', $document),
                data: ['document_id' => $document->id]
            );
        }
    }
}

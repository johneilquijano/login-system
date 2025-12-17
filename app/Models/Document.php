<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'org_id',
        'title',
        'description',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'status',
        'signed_at',
        'signature_data',
        'signature_type',
        'reviewed_at',
        'reviewed_by',
        'review_notes',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    /**
     * Scope to filter documents by organization
     */
    public function scopeForOrganization($query, $orgId)
    {
        return $query->where('org_id', $orgId);
    }

    /**
     * Scope to filter documents by pending status
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending_review');
    }

    /**
     * Get formatted file type label
     */
    public function getFormattedTypeAttribute()
    {
        if (!$this->mime_type) {
            return 'Document';
        }

        $mimeMap = [
            'application/pdf' => 'PDF',
            'application/msword' => 'DOC',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'DOCX',
            'application/vnd.ms-excel' => 'XLS',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'XLSX',
            'image/jpeg' => 'JPG',
            'image/png' => 'PNG',
            'image/gif' => 'GIF',
            'text/plain' => 'TXT',
        ];

        return $mimeMap[$this->mime_type] ?? str_replace('application/', '', $this->mime_type);
    }
}

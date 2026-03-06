<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StationaryRequest extends Model
{
    protected $table = 'requests';

    protected $fillable = ['department_id', 'requested_by', 'status', 'total_amount'];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user who requested this (could be teacher or hod)
     * Since we don't have a central users table, we need to check approvals table for role info
     */
    public function requestedBy()
    {
        // Get the first approval (if any) to determine the requesting user's role
        $firstApproval = $this->approvals()->first();
        
        if ($firstApproval && $firstApproval->role === 'hod') {
            return Hod::find($this->requested_by);
        }
        
        // Default to teacher
        return Teacher::find($this->requested_by);
    }
    
    /**
     * Get the requester as a relationship (for eager loading)
     */
    public function requester()
    {
        // Try to determine from approvals, but return both possibilities for now
        // This is mainly for display purposes
        $teacher = Teacher::find($this->requested_by);
        if ($teacher) {
            return $teacher;
        }
        return Hod::find($this->requested_by);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RequestItem::class, 'request_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class, 'request_id');
    }

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isHodApproved(): bool
    {
        return $this->status === 'hod_approved';
    }

    public function isPrincipalApproved(): bool
    {
        return $this->status === 'principal_approved';
    }

    public function isTrustApproved(): bool
    {
        return $this->status === 'trust_approved';
    }

    public function isSentToProvider(): bool
    {
        return $this->status === 'sent_to_provider';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

}

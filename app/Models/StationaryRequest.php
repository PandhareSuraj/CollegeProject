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
        // Provide a proper Eloquent relation so callers using eager-loading
        // (e.g. ->with('requestedBy')) do not error. We default this relation
        // to Teacher; Hod is handled via a separate relation and an accessor
        // that returns the actual requester (Teacher or Hod).
        return $this->belongsTo(Teacher::class, 'requested_by');
    }
    /**
     * Relationship when the requester is a Teacher
     */
    public function requestedByTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'requested_by');
    }

    /**
     * Relationship when the requester is a Hod
     */
    public function requestedByHod(): BelongsTo
    {
        return $this->belongsTo(Hod::class, 'requested_by');
    }

    /**
     * Accessor that returns the actual requester model (Teacher or Hod).
     * Use `$request->requester` to access the model without conflicting with
     * the `requested_by` DB column.
     */
    public function getRequesterAttribute()
    {
        if ($this->relationLoaded('requestedByTeacher') && $this->requestedByTeacher) {
            return $this->requestedByTeacher;
        }

        if ($this->relationLoaded('requestedByHod') && $this->requestedByHod) {
            return $this->requestedByHod;
        }

        // Fallback: avoid loading other relations here to prevent recursive
        // resolution during view rendering. Prefer Teacher, then Hod.
        // Read the raw attribute value directly to avoid triggering any
        // potential accessors.
        $requestedById = $this->getAttributeFromArray('requested_by') ?? null;

        if (!$requestedById) {
            return null;
        }

        $teacher = \App\Models\Teacher::find($requestedById);
        if ($teacher) {
            return $teacher;
        }

        return \App\Models\Hod::find($requestedById);
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

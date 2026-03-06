<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    protected $fillable = ['request_id', 'approved_by', 'role', 'status', 'remarks'];

    public function request(): BelongsTo
    {
        return $this->belongsTo(StationaryRequest::class, 'request_id');
    }

    /**
     * Get the user who gave this approval (HOD, Principal, Trust Head, etc)
     * Uses the role attribute to determine which model to query
     */
    public function approver()
    {
        $roleModelMap = [
            'teacher' => Teacher::class,
            'hod' => Hod::class,
            'principal' => Principal::class,
            'trust_head' => TrustHead::class,
            'provider' => Provider::class,
            'admin' => Administrator::class,
        ];

        $modelClass = $roleModelMap[$this->role] ?? Teacher::class;

        // Return a BelongsTo relation so Eloquent can eager-load the approver.
        // We use the dynamic related model class based on role.
        return $this->belongsTo($modelClass, 'approved_by');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}

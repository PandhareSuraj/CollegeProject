<?php

namespace App\Enums;

enum RequestStatus: string
{
    case PENDING = 'pending';
    case HOD_APPROVED = 'hod_approved';
    case PRINCIPAL_APPROVED = 'principal_approved';
    case TRUST_APPROVED = 'trust_approved';
    case SENT_TO_PROVIDER = 'sent_to_provider';
    case COMPLETED = 'completed';
    case REJECTED = 'rejected';

    /**
     * Get human-readable label
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::HOD_APPROVED => 'HOD Approved',
            self::PRINCIPAL_APPROVED => 'Principal Approved',
            self::TRUST_APPROVED => 'Trust Approved',
            self::SENT_TO_PROVIDER => 'Sent to Provider',
            self::COMPLETED => 'Completed',
            self::REJECTED => 'Rejected',
        };
    }

    /**
     * Get badge color
     */
    public function badgeColor(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::HOD_APPROVED => 'info',
            self::PRINCIPAL_APPROVED => 'primary',
            self::TRUST_APPROVED => 'success',
            self::SENT_TO_PROVIDER => 'secondary',
            self::COMPLETED => 'success',
            self::REJECTED => 'danger',
        };
    }

    /**
     * Get next status in workflow
     */
    public function nextStatus(): ?self
    {
        return match ($this) {
            self::PENDING => self::HOD_APPROVED,
            self::HOD_APPROVED => self::PRINCIPAL_APPROVED,
            self::PRINCIPAL_APPROVED => self::TRUST_APPROVED,
            self::TRUST_APPROVED => self::SENT_TO_PROVIDER,
            self::SENT_TO_PROVIDER => self::COMPLETED,
            default => null,
        };
    }

    /**
     * Check if status is rejected or completed
     */
    public function isFinal(): bool
    {
        return $this === self::REJECTED || $this === self::COMPLETED;
    }

    /**
     * Get all status values for database enums
     */
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}

enum ApprovalRole: string
{
    case ADMIN = 'admin';
    case TEACHER = 'teacher';
    case HOD = 'hod';
    case PRINCIPAL = 'principal';
    case TRUST_HEAD = 'trust_head';
    case PROVIDER = 'provider';

    /**
     * Get label
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::TEACHER => 'Teacher',
            self::HOD => 'Head of Department',
            self::PRINCIPAL => 'Principal',
            self::TRUST_HEAD => 'Trust Head',
            self::PROVIDER => 'Provider',
        };
    }

    /**
     * Get all role values
     */
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}

enum ApprovalStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    /**
     * Get label
     */
    public function label(): string
    {
        return ucfirst($this->value);
    }

    /**
     * Get badge color
     */
    public function badgeColor(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
        };
    }
}

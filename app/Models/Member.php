<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MemberPhoto;

class Member extends Model
{
    protected $table = 'members';

    protected $connection = 'site';

    public $timestamps = false;

    protected $guarded = [];

    /**
     * Format height stored in the database.
     *
     * Examples:
     * 5.7  -> 5ft 7in
     * 5.10 -> 5ft 10in
     * 5.11 -> 5ft 11in
     * 6    -> 6ft 0in
     */
    public function formatHeight($height): string
    {
        if ($height === null || $height === '') {
            return '-';
        }

        $height = trim((string) $height);

        if ($height === '') {
            return '-';
        }

        $parts = explode('.', $height, 2);

        $feet = (int) $parts[0];

        $inches = isset($parts[1])
            ? (int) $parts[1]
            : 0;

        return "{$feet}ft {$inches}in";
    }

    /**
     * Get formatted partner height from.
     */
    public function getPartnerHeightFromFormattedAttribute(): string
    {
        return $this->formatHeight($this->partner_height_from);
    }

    /**
     * Get formatted partner height to.
     */
    public function getPartnerHeightToFormattedAttribute(): string
    {
        return $this->formatHeight($this->partner_height_to);
    }

    /**
     * Calculate profile completion percentage.
     *
     * Counts fields in the members table that contain a value.
     */
    public function getProfileCompletionAttribute(): int
    {
        $excludedFields = [
            'id',
            'password',
            'google_token',
            'photo_password',
        ];

        $attributes = $this->getAttributes();

        $totalFields = 0;
        $completedFields = 0;

        foreach ($attributes as $field => $value) {

            if (in_array($field, $excludedFields)) {
                continue;
            }

            $totalFields++;

            if ($value !== null && trim((string) $value) !== '') {
                $completedFields++;
            }
        }

        if ($totalFields === 0) {
            return 0;
        }

        return (int) round(
            ($completedFields / $totalFields) * 100
        );
    }

    /**
     * Get number of completed profile fields.
     */
    public function getCompletedFieldsAttribute(): int
    {
        $excludedFields = [
            'id',
            'password',
            'google_token',
            'photo_password',
        ];

        $completed = 0;

        foreach ($this->getAttributes() as $field => $value) {

            if (in_array($field, $excludedFields)) {
                continue;
            }

            if ($value !== null && trim((string) $value) !== '') {
                $completed++;
            }
        }

        return $completed;
    }

    /**
     * Get total profile fields considered for completion.
     */
    public function getTotalFieldsAttribute(): int
    {
        $excludedFields = [
            'id',
            'password',
            'google_token',
            'photo_password',
        ];

        return count(
            array_diff(
                array_keys($this->getAttributes()),
                $excludedFields
            )
        );
    }

    /**
     * Determine whether the member is active.
     *
     * Active values are based on "Yes".
     */
    public function getIsActiveAttribute(): bool
    {
        return strtolower(trim((string) $this->active)) === 'yes';
    }

    /**
     * Determine whether the member is inactive.
     *
     * Inactive values are NULL or "No".
     */
    public function getIsInactiveAttribute(): bool
    {
        return !$this->active;
    }

    public function photos()
    {
        return $this->hasMany(MemberPhoto::class, 'member_id', 'id');
    }
}

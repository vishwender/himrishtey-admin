<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\MemberPhoto;
use App\Models\MemberRotation;

class Member extends Model
{
    protected $table = 'members';

    protected $connection = 'site';

    public $timestamps = false;

    protected $guarded = [];


    /*
    |--------------------------------------------------------------------------
    | Format Height
    |--------------------------------------------------------------------------
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


    /*
    |--------------------------------------------------------------------------
    | Partner Height
    |--------------------------------------------------------------------------
    */

    public function getPartnerHeightFromFormattedAttribute(): string
    {
        return $this->formatHeight(
            $this->partner_height_from
        );
    }


    public function getPartnerHeightToFormattedAttribute(): string
    {
        return $this->formatHeight(
            $this->partner_height_to
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Profile Completion
    |--------------------------------------------------------------------------
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

            if (
                $value !== null &&
                trim((string) $value) !== ''
            ) {
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


    /*
    |--------------------------------------------------------------------------
    | Completed Profile Fields
    |--------------------------------------------------------------------------
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

            if (
                $value !== null &&
                trim((string) $value) !== ''
            ) {
                $completed++;
            }
        }

        return $completed;
    }


    /*
    |--------------------------------------------------------------------------
    | Total Profile Fields
    |--------------------------------------------------------------------------
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


    /*
    |--------------------------------------------------------------------------
    | Active Status
    |--------------------------------------------------------------------------
    */

    public function getIsActiveAttribute(): bool
    {
        return strtolower(
            trim((string) $this->active)
        ) === 'yes';
    }


    /*
    |--------------------------------------------------------------------------
    | Inactive Status
    |--------------------------------------------------------------------------
    */

    public function getIsInactiveAttribute(): bool
    {
        return !$this->is_active;
    }


    /*
    |--------------------------------------------------------------------------
    | Member Photos
    |--------------------------------------------------------------------------
    */

    public function photos(): HasMany
    {
        return $this->hasMany(
            MemberPhoto::class,
            'member_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Member Rotations
    |--------------------------------------------------------------------------
    */

    public function rotations(): HasMany
    {
        return $this->hasMany(
            MemberRotation::class,
            'member_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Latest Rotation
    |--------------------------------------------------------------------------
    */

    public function latestRotation(): HasOne
    {
        return $this->hasOne(
            MemberRotation::class,
            'member_id',
            'id'
        )->latestOfMany();
    }

    protected function generateProfileId(int $memberId): string
    {
        $database = DB::connection('site')
            ->getDatabaseName();

        $prefixes = [
            'himrishtey_main'       => 'HIM',
            'himrishtey_gallpakki'  => 'PB',
            'himrishtey_dogririshtey' => 'DR',
        ];

        $prefix = $prefixes[$database] ?? null;

        if (!$prefix) {
            throw new \RuntimeException(
                "No profile ID prefix configured for database: {$database}"
            );
        }

        return $prefix . $memberId;
    }
}

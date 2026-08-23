<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberRotation extends Model
{
    protected $table = 'member_rotations';

    protected $connection = 'site';

    protected $guarded = [];

    protected $casts = [
        'next_rotation_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Member
    |--------------------------------------------------------------------------
    */

    public function member(): BelongsTo
    {
        return $this->belongsTo(
            Member::class,
            'member_id',
            'id'
        );
    }
}

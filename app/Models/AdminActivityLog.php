<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
    protected $fillable = [
        'admin_id',
        'site_id',
        'action',
        'module',
        'description',
        'ip_address',
    ];

    public function admin()
    {
        return $this->belongsTo(
            Admin::class
        );
    }

    public function site()
    {
        return $this->belongsTo(
            Site::class
        );
    }
}

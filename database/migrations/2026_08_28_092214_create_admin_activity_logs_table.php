<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_activity_logs', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Staff / Admin
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('admin_id')->nullable()->index();

            /*
            |--------------------------------------------------------------------------
            | Site
            |--------------------------------------------------------------------------
            |
            | Central sites.id
            |
            */

            $table->unsignedBigInteger('site_id')->nullable()->index();

            /*
            |--------------------------------------------------------------------------
            | Action
            |--------------------------------------------------------------------------
            |
            | login
            | logout
            | member_viewed
            | contact_viewed
            | remark_added
            | rotation_added
            | rotation_completed
            | member_updated
            | etc.
            |
            */

            $table->string('action', 100)->index();

            /*
            |--------------------------------------------------------------------------
            | Related record
            |--------------------------------------------------------------------------
            */

            $table->string('subject_type', 100)->nullable()->index();

            $table->unsignedBigInteger('subject_id')->nullable()->index();

            /*
            |--------------------------------------------------------------------------
            | Member
            |--------------------------------------------------------------------------
            |
            | Member IDs belong to the selected site database.
            |
            */

            $table->unsignedBigInteger('member_id')->nullable()->index();

            /*
            |--------------------------------------------------------------------------
            | Human readable information
            |--------------------------------------------------------------------------
            */

            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Additional information
            |--------------------------------------------------------------------------
            |
            | Previous value
            | New value
            | Rotation days
            | Remarks
            | etc.
            |
            */

            $table->json('metadata')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Request Information
            |--------------------------------------------------------------------------
            */

            $table->string('ip_address', 45)->nullable();

            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index([
                'admin_id',
                'created_at',
            ]);

            $table->index([
                'site_id',
                'created_at',
            ]);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};
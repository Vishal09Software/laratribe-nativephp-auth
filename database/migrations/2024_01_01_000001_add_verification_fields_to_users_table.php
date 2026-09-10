<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds email_verified_at to the users table if it does not already
     * exist. Laravel's default users table already ships with this
     * column, so this migration is a safe no-op in the common case.
     */
    public function up(): void
    {
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'email_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Intentionally left blank — we never want to drop a column
        // we may not have created ourselves.
    }
};

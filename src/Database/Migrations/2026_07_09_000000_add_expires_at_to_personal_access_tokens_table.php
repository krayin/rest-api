<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sanctum 4.x reads/writes an `expires_at` column on personal_access_tokens
 * that did not exist in the older 2.x migration. Installations upgraded from
 * a pre-4.x Sanctum (e.g. Krayin's original 2.1.x scaffold) are missing it,
 * which breaks token issuance/validation with a "column not found" error.
 * This backfills the column only when the table exists and doesn't have it
 * already, so it is a no-op on fresh installs where Sanctum's own migration
 * already created it.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('personal_access_tokens')) {
            return;
        }

        if (Schema::hasColumn('personal_access_tokens', 'expires_at')) {
            return;
        }

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            if (Schema::hasColumn('personal_access_tokens', 'last_used_at')) {
                $table->timestamp('expires_at')->nullable()->index()->after('last_used_at');
            } else {
                $table->timestamp('expires_at')->nullable()->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('personal_access_tokens')) {
            return;
        }

        if (! Schema::hasColumn('personal_access_tokens', 'expires_at')) {
            return;
        }

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            /**
             * Drop the index before the column: SQLite refuses to drop a column
             * that is still referenced by an index (the `->index()` added in
             * up()), which would otherwise make the rollback fail.
             */
            $table->dropIndex(['expires_at']);
            $table->dropColumn('expires_at');
        });
    }
};

<?php

namespace Webkul\RestApi\Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Webkul\RestApi\Tests\TestCase;

/**
 * Guards the backfill migration that adds `expires_at` to
 * personal_access_tokens for installs upgraded from a pre-Sanctum-4 schema
 * (Krayin's original 2.1.x scaffold never had the column, but Sanctum 4.x
 * reads/writes it on every token operation). Confirms the migration is
 * picked up via loadMigrationsFrom() and is safe whether the legacy table is
 * missing the column, already has it, or the table doesn't exist yet.
 */
class PersonalAccessTokensMigrationTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
    }

    public function test_it_adds_expires_at_to_a_legacy_personal_access_tokens_table(): void
    {
        $this->createLegacyPersonalAccessTokensTable();

        $this->assertFalse(Schema::hasColumn('personal_access_tokens', 'expires_at'));

        $this->artisan('migrate', ['--force' => true])->run();

        $this->assertTrue(Schema::hasColumn('personal_access_tokens', 'expires_at'));
    }

    public function test_it_is_a_no_op_when_the_column_already_exists(): void
    {
        $this->createLegacyPersonalAccessTokensTable();

        Schema::table('personal_access_tokens', function ($table) {
            $table->timestamp('expires_at')->nullable();
        });

        // Should not throw a "duplicate column" error on an already-current schema.
        $this->artisan('migrate', ['--force' => true])->run();

        $this->assertTrue(Schema::hasColumn('personal_access_tokens', 'expires_at'));
    }

    public function test_it_is_a_no_op_when_the_table_does_not_exist(): void
    {
        $this->assertFalse(Schema::hasTable('personal_access_tokens'));

        $this->artisan('migrate', ['--force' => true])->run();

        $this->assertFalse(Schema::hasTable('personal_access_tokens'));
    }

    public function test_it_adds_expires_at_when_the_legacy_table_lacks_last_used_at(): void
    {
        // Exercises the up() else-branch (add the column without ->after()).
        Schema::create('personal_access_tokens', function ($table) {
            $table->id();
            $table->morphs('tokenable');
            $table->text('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamps();
        });

        $this->assertFalse(Schema::hasColumn('personal_access_tokens', 'last_used_at'));
        $this->assertFalse(Schema::hasColumn('personal_access_tokens', 'expires_at'));

        $this->artisan('migrate', ['--force' => true])->run();

        $this->assertTrue(Schema::hasColumn('personal_access_tokens', 'expires_at'));
    }

    public function test_down_drops_the_backfilled_column_but_keeps_the_table(): void
    {
        $this->createLegacyPersonalAccessTokensTable();

        $this->artisan('migrate', ['--force' => true])->run();
        $this->assertTrue(Schema::hasColumn('personal_access_tokens', 'expires_at'));

        $this->artisan('migrate:rollback', ['--force' => true])->run();

        $this->assertTrue(Schema::hasTable('personal_access_tokens'));
        $this->assertFalse(Schema::hasColumn('personal_access_tokens', 'expires_at'));
    }

    /**
     * Mirrors Sanctum's pre-4.x migration shape (no `expires_at` column).
     */
    private function createLegacyPersonalAccessTokensTable(): void
    {
        Schema::create('personal_access_tokens', function ($table) {
            $table->id();
            $table->morphs('tokenable');
            $table->text('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }
}

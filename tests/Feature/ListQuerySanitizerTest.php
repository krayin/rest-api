<?php

namespace Webkul\RestApi\Tests\Feature;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Webkul\RestApi\Http\Controllers\V1\Concerns\SanitizesListQuery;
use Webkul\RestApi\Tests\Fixtures\Widget;
use Webkul\RestApi\Tests\TestCase;

/**
 * Covers the list-query sanitizer that every V1 list endpoint relies on, WITHOUT
 * a live Krayin instance: it drives a throwaway sqlite-backed model directly.
 *
 * This is the security guard that used to be exercised only by the env-gated
 * integration suite — it proves that attacker-controlled sort/filter column
 * names are rejected/ignored (never interpolated into SQL) and that array-form
 * filters do not blow up with a 500.
 */
class ListQuerySanitizerTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function defineDatabaseMigrations(): void
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('priority');
        });

        Widget::insert([
            ['name' => 'alpha', 'priority' => 3],
            ['name' => 'beta',  'priority' => 1],
            ['name' => 'gamma', 'priority' => 2],
        ]);
    }

    /**
     * An object that exposes the protected trait method for testing.
     */
    private function sanitize(array $params)
    {
        $sanitizer = new class
        {
            use SanitizesListQuery;

            public function run(Builder $query, Request $request)
            {
                return $this->applyListQuery($query, $request);
            }
        };

        return $sanitizer->run(Widget::query(), Request::create('/widgets', 'GET', $params));
    }

    public function test_unknown_sort_column_is_rejected_with_422(): void
    {
        $this->expectException(ValidationException::class);

        $this->sanitize(['sort' => 'id); DROP TABLE widgets;--']);
    }

    public function test_valid_sort_column_orders_results(): void
    {
        $result = $this->sanitize(['sort' => 'priority', 'order' => 'asc', 'pagination' => 0]);

        $this->assertSame([1, 2, 3], $result->pluck('priority')->all());
    }

    public function test_unknown_filter_column_is_ignored_never_reaches_the_query(): void
    {
        // A parameter whose name is not a real column must not be applied (no
        // 500, no filtering) — the core SQL-injection guard.
        $result = $this->sanitize(['not_a_real_column' => "x') OR 1=1--", 'pagination' => 0]);

        $this->assertCount(3, $result);
    }

    public function test_known_filter_column_filters_rows(): void
    {
        $result = $this->sanitize(['name' => 'beta', 'pagination' => 0]);

        $this->assertCount(1, $result);
        $this->assertSame('beta', $result->first()->name);
    }

    public function test_array_form_filter_does_not_raise_500(): void
    {
        // ?priority[]=1&priority[]=2 previously exploded an array -> TypeError.
        $result = $this->sanitize(['priority' => ['1', '2'], 'pagination' => 0]);

        $this->assertEqualsCanonicalizing([1, 2], $result->pluck('priority')->all());
    }

    public function test_comma_separated_filter_matches_multiple(): void
    {
        $result = $this->sanitize(['priority' => '1,3', 'pagination' => 0]);

        $this->assertEqualsCanonicalizing([1, 3], $result->pluck('priority')->all());
    }

    public function test_pagination_is_on_by_default(): void
    {
        $this->assertInstanceOf(LengthAwarePaginator::class, $this->sanitize([]));
    }
}

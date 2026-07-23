<?php

namespace Webkul\RestApi\Http\Controllers\V1\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

/**
 * Encapsulates the list-endpoint query shaping shared by every V1 controller:
 * allow-listed column filtering, validated sorting, and pagination.
 *
 * Extracted from the base controller so the logic operates on a plain Eloquent
 * builder (no repository / Krayin core dependency) and can be unit-tested on its
 * own — it owns the SQL-injection guard (unknown filter/sort columns are never
 * passed through to the database) that the REST layer relies on.
 */
trait SanitizesListQuery
{
    /**
     * Default page size used when the request does not specify a `limit`.
     */
    public const DEFAULT_PER_PAGE = 10;

    /**
     * Query-string keys that are pagination/sorting controls, not filters.
     *
     * @var array<int, string>
     */
    protected $excludeKeys = [
        'entity_type',
        'limit',
        'page',
        'pagination',
        'order',
        'sort',
    ];

    /**
     * Apply the request's filters, sort and pagination to a query builder.
     *
     * - Filters referencing a column the table does not have are silently
     *   ignored (never interpolated into the query) so an attacker-controlled
     *   parameter name cannot reach the database.
     * - An unknown `sort` column raises a 422 ValidationException rather than a
     *   500 database error.
     * - Both the comma-separated (`?col=a,b`) and array (`?col[]=a&col[]=b`)
     *   filter forms are accepted.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    protected function applyListQuery(Builder $query, ?Request $request = null)
    {
        $request ??= request();

        /**
         * Memoize the column listing per table for the request lifecycle so a
         * list endpoint does not issue a schema-introspection query on every
         * call.
         */
        static $columnCache = [];

        $table   = $query->getModel()->getTable();
        $columns = $columnCache[$table] ??= Schema::getColumnListing($table);

        foreach ($request->except($this->excludeKeys) as $input => $value) {
            if (! in_array($input, $columns, true)) {
                continue;
            }

            $values = is_array($value) ? $value : explode(',', (string) $value);

            $query->whereIn($input, array_map(fn ($v) => trim((string) $v), $values));
        }

        if ($sort = $request->input('sort')) {
            if (! in_array($sort, $columns, true)) {
                throw ValidationException::withMessages([
                    'sort' => trans('validation.in', ['attribute' => 'sort']),
                ]);
            }

            $order = strtolower((string) $request->input('order'));
            $order = in_array($order, ['asc', 'desc'], true) ? $order : 'desc';

            $query->orderBy($sort, $order);
        } else {
            $query->orderBy('id', 'desc');
        }

        if (is_null($request->input('pagination')) || $request->input('pagination')) {
            return $query->paginate($request->input('limit') ?? self::DEFAULT_PER_PAGE);
        }

        return $query->get();
    }
}

<?php

namespace Lecturize\Tags\Traits;

use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use Lecturize\Tags\Models\Tag;

/**
 * Class ModelGetter
 * @package Lecturize\Tags\Traits
 */
trait ModelGetter
{
    /**
     * Retrieve tags.
     *
     * @param  int|null  $limit
     * @return Collection
     * @throws Exception
     */
    public function getTags(?int $limit = null): Collection
    {
        return app(config('lecturize.tags.model', Tag::class))::latest()->take($limit)->get();
    }

    /**
     * Retrieve paginated tags.
     *
     * @param  int   $per_page
     * @param  bool  $cached
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function getTagsPaginated($per_page = 15, $cached = true): LengthAwarePaginator
    {
        $page = request()->get('page', '1');

        $key = 'tags.paginate-'. $per_page .'.page-'. $page;

        if (! $cached)
            cache()->forget($key);

        return cache()->remember($key, now()->addWeek(), function() use($per_page) {
            return app(config('lecturize.tags.model', Tag::class))::paginate($per_page);
        });
    }
}
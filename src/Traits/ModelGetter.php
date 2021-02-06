<?php namespace Lecturize\Tags\Traits;

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
     * @param  int  $count
     * @return Collection
     * @throws Exception
     */
    public function getTags($count = null): Collection
    {
        return Tag::latest()
                  ->take($count)
                  ->get();
    }

    /**
     * Retrieve paginated tags.
     *
     * @param  int      $per_page
     * @param  boolean  $cached
     * @return Collection|LengthAwarePaginator
     * @throws Exception
     */
    public function getTagsPaginated($per_page = 15, $cached = true): LengthAwarePaginator
    {
        $page = request()->get('page', '1');

        $key = 'tags.paginate-'. $per_page .'.page-'. $page;

        if (! $cached)
            cache()->forget($key);

        return cache()->remember($key, now()->addWeek(), function() use($per_page) {
            return Tag::paginate($per_page);
        });
    }
}
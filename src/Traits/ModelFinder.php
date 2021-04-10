<?php namespace Lecturize\Tags\Traits;

use Lecturize\Tags\Models\Tag;

/**
 * Class ModelFinder
 * @package Lecturize\Tags\Traits
 */
trait ModelFinder
{
    /**
     * Find a tag.
     *
     * @param  string  $slug
     * @return Tag|null
     */
    public function findTag(string $slug): ?Tag
    {
        return app(config('lecturize.tags.model', Tag::class))::whereSlug($slug)->first();
    }
}
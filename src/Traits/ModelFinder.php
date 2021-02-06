<?php namespace Lecturize\Tags\Traits;

use Lecturize\Tags\Models\Tag;

/**
 * Class ModelFinder
 * @package Lecturize\Tags\Traits
 */
trait ModelFinder
{
    /**
     * Find tag.
     *
     * @param  string  $slug
     * @return Tag
     */
    public function findTag($slug): Tag
    {
        return Tag::whereSlug($slug)->first();
    }
}
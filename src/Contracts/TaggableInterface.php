<?php namespace Lecturize\Tags\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

/**
 * Interface TaggableInterface
 * @package Lecturize\Tags\Contracts
 */
interface TaggableInterface
{
    /**
     * @return MorphToMany
     */
    public function tags(): MorphToMany;

    /**
     * @return MorphMany
     */
    public function tagged(): MorphMany;

    /**
     * @param  string|array|Collection  $tags
     * @return self
     */
    public function tag($tags): self;

    /**
     * @param  string|array|Collection  $tags
     * @return self
     */
    public function untag($tags): self;

    /**
     * @param  string|array|Collection  $tags
     * @return self
     */
    public function retag($tags): self;

    /**
     * @return self
     */
    public function detag(): self;

    /**
     * @param  string  $tag
     * @return bool
     */
    public function hasTag(string $tag): bool;

    /**
     * @return array
     */
    public function listTags(): array;
}
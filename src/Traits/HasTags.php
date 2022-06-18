<?php

namespace Lecturize\Tags\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Lecturize\Tags\Models\Tag;
use Lecturize\Tags\Models\Taggable;

/**
 * Class HasTags
 * @package Lecturize\Traits\Tags
 * @property EloquentCollection  $tags
 * @property EloquentCollection  $tagged
 */
trait HasTags
{
    /**
     * Get all tags for this model.
     *
     * @return MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(config('lecturize.tags.model', Tag::class), 'taggable');
    }

    /**
     * Return collection of tagged rows related to the tagged model
     *
     * @return MorphMany
     */
    public function tagged(): MorphMany
    {
        return $this->morphMany(config('lecturize.tags.model_pivot', Taggable::class), 'taggable');
    }

    /**
     * Sync tag relation adding new tags as needed
     *
     * @param  string|array|Collection  $tags
     * @return self
     */
    public function tag($tags): self
    {
        $tags = $this->makeTagsArray($tags);

        $this->addTags($tags);

        if (count($tags) > 0) {
            $this->tags()->sync(
                app(config('lecturize.tags.model', Tag::class))::whereIn('tag', $tags)->pluck('id')->all()
            );

            return $this;
        }

        $this->tags()->detach();
        return $this;
    }

    /**
     * Removes given tags from model
     *
     * @param  string|array|Collection  $tags
     * @return self
     */
    public function untag($tags): self
    {
        $tags = $this->makeTagsArray($tags);

        foreach ($tags as $tag)
            $this->removeOneTag($tag);

        return $this;
    }

    /**
     * Replaces all existing tags for a model with new ones
     *
     * @param  string|array|Collection  $tags
     * @return self
     */
    public function retag($tags): self
    {
        $this->detag()->tag($tags);

        return $this;
    }

    /**
     * Removes all existing tags from model
     *
     * @return self
     */
    public function detag(): self
    {
        $this->removeAllTags();

        return $this;
    }

    /**
     * Check if current model is tagged with a given tag
     *
     * @param  string  $tag
     * @return bool
     */
    public function hasTag(string $tag): bool
    {
        return $this->tags->contains('tag', $tag);
    }

    /**
     * Convenience method to list all tags
     *
     * @return Collection
     */
    public function listTags(): Collection
    {
        return $this->tags->pluck('tag');
    }

    /**
     * Get an array of all tagged tags
     *
     * @return array
     */
    public function getTagsArray(): array
    {
        return $this->listTags()->toArray();
    }

    /**
     * A static function to add any tags that aren't in the database already.
     *
     * @param array  $tags  List of tags to check/add.
     */
    public static function addTags(array $tags)
    {
        if (count($tags) === 0)
            return;

        $found = app(config('lecturize.tags.model', Tag::class))::whereIn('tag', $tags)->pluck('tag')->all();

        foreach (array_diff($tags, $found) as $tag) {
            if (empty(trim($tag)) || strlen($tag) < 3)
                continue;

            try {
                $model = new Tag;
                $model->tag = trim($tag);
                $model->save();
            } catch (\Illuminate\Database\QueryException $e) {
                $context = [];
                $context['file'] = $e->getFile() ?: '';
                $context['line'] = $e->getLine() ?: '';
                $context['code'] = $e->getCode() ?: '';

                logger()->warning($e->getMessage(), $context);
            }
        }
    }

    /**
     * Remove one tag.
     *
     * @param string  $tag
     */
    protected function removeOneTag(string $tag) {
        if ($tag = app(config('lecturize.tags.model', Tag::class))::findBy('tag', $tag))
            $this->tags()->detach($tag);
    }

    /**
     * Remove all tags.
     */
    protected function removeAllTags()
    {
        $this->tags()->sync([]);
    }

    /**
     * Prepare tags array.
     *
     * @param  string|array|Collection  $tags
     * @return array
     */
    public static function makeTagsArray($tags): array
    {
        if (is_array($tags)) {
            $tags = array_unique(array_filter($tags));
        } elseif (is_string($tags)) {
            $tags = preg_split('#[' . preg_quote(',', '#' ) . ']#', $tags, null, PREG_SPLIT_NO_EMPTY);
        } elseif (method_exists($tags, 'toArray')) {
            $tags = $tags->toArray();
        } else {
            return [];
        }

        $tagSet = [];
        foreach ($tags as $tag)
            $tagSet[] = app(config('lecturize.tags.model', Tag::class))::taggify($tag);

        return $tagSet;
    }

    /**
     * Filter model to subset with the given tags
     *
     * @param  Builder  $query
     * @param  string   $tag
     * @return Builder
     */
    public function scopeWithTag(Builder $query, string $tag): Builder
    {
        $tag = app(config('lecturize.tags.model', Tag::class))::where('tag', $tag)->first();

        return $query->whereHas('tagged', function($q) use($tag) {
                            $q->where('tag_id', $tag->id);
                        });
    }

    /**
     * Filter model to subset with the given tags
     *
     * @param  Builder                  $query
     * @param  string|array|Collection  $tags
     * @return Builder
     */
    public function scopeWithTags(Builder $query, $tags): Builder
    {
        $tags = $this->makeTagsArray($tags);

        foreach ($tags as $tag)
            $this->scopeWithTag($query, $tag);

        return $query;
    }

    /**
     * Filter model to subset with the given tags
     *
     * @param  Builder                  $query
     * @param  string|array|Collection  $tags
     * @return Builder
     */
    public function scopeWithAnyTag(Builder $query, $tags): Builder
    {
        $tags = $this->makeTagsArray($tags);

        $tag_ids = app(config('lecturize.tags.model', Tag::class))::whereIn('tag', $tags)->pluck('id');

        $taggable_ids = app(config('lecturize.tags.model_pivot', Taggable::class))::whereIn('tag_id', $tag_ids)->where('taggable_type', $query->getModel()->getMorphClass())->pluck('taggable_id');

        return $query->whereIn($this->getTable() .'.id', $taggable_ids);
    }
}
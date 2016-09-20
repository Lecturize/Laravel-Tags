<?php namespace vendocrat\Tags;

use vendocrat\Tags\Models\Tag;
use vendocrat\Tags\Models\Taggable;

/**
 * Class TaggableTrait
 * @package vendocrat\Tags
 */
trait TaggableTrait
{
	/**
	 * Get all tags for this model.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
	 */
	public function tags()
	{
		return $this->morphToMany(Tag::class, 'taggable');
	}

	/**
	 * Return collection of tagged rows related to the tagged model
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function tagged()
	{
		return $this->morphMany(Taggable::class, 'taggable');
	}

	/**
	 * Sync tag relation adding new tags as needed
	 *
	 * @param string|array $tags
	 */
	public function tag( $tags )
	{
		$tags = $this->makeTagsArray($tags);

		$this->addTags($tags);

		if ( count($tags) > 0 ) {
			$this->tags()->sync(
				Tag::whereIn( 'tag', $tags )->pluck('id')->all()
			);

			return;
		}

		$this->tags()->detach();
	}

	/**
	 * Removes given tags from model
	 *
	 * @param $tags
	 * @return $this
	 */
	public function untag( $tags ) {
		$tags = $this->makeTagsArray($tags);

		foreach ( $tags as $tag ) {
			$this->removeOneTag($tag);
		}

		return $this;
	}

	/**
	 * Replaces all existing tags for a model with new ones
	 *
	 * @param  $tags
	 * @return $this
	 */
	public function retag( $tags ) {
		$this->detag()->tag($tags);

		return $this;
	}

	/**
	 * Removes all existing tags from model
	 *
	 * @return true
	 */
	public function detag() {
		$this->removeAllTags();

		return $this;
	}

	/**
	 * Check if current model is tagged with a given tag
	 *
	 * @param string $tag
	 * @return bool
	 */
	public function hasTag( $tag )
	{
		return $this->tags->contains('tag', $tag);
	}

	/**
	 * Convenience method to list all tags
	 *
	 * @return mixed
	 */
	public function listTags()
	{
		return $this->tags->lists('tag');
	}

	/**
	 * Get an array of all tagged tags
	 *
	 * @return mixed
	 */
	public function getTagsArray()
	{
		return $this->listTags()->toArray();
	}

	/**
	 * A static function to add any tags that
	 * aren’t in the database already.
	 *
	 * @param array $tags List of tags to check/add
	 */
	public static function addTags( array $tags )
	{
		if ( count($tags) === 0 )
			return;

		$found = Tag::whereIn( 'tag', $tags )->lists( 'tag' )->all();

		foreach ( array_diff( $tags, $found ) as $tag ) {
			if ( ! empty(trim($tag)) && strlen($tag) >= 3 )
				Tag::updateOrCreate(['tag' => trim($tag)]);
		}
	}

	/**
	 * @param $tag
	 */
	protected function removeOneTag( $tag ) {
		if ( $tag = Tag::findBy( 'tag', $tag ) ) {
			$this->tags()->detach($tag);
		}
	}

	/**
	 * @return mixed
	 */
	protected function removeAllTags() {
		$this->tags()->sync([]);
	}

	/**
	 * TODO: not playing nicely with collection yet
	 *
	 * @param  string|array $tags
	 * @return array
	 */
	public static function makeTagsArray( $tags ) {
		if ( is_array($tags) ) {
			$tags = array_unique(array_filter($tags));
		} else if ( is_string($tags) ) {
			$tags = preg_split('#[' . preg_quote( ',', '#' ) . ']#', $tags, null, PREG_SPLIT_NO_EMPTY);
		} else {
			$tags = $tags->toArray();
		}

		$tagSet = [];
		foreach ( $tags as $tag ) {
			$tagSet[] = Tag::taggify($tag);
		}

		return $tagSet;
	}

	/**
	 * Filter model to subset with the given tags
	 *
	 * @param $query
	 * @param $tag string
	 * @return
	 */
	public function scopeWithTag( $query, $tag )
	{
		$tag = Tag::where('tag', $tag)->first();

		return $query->whereHas('tagged', function($q) use($tag) {
			$q->where( 'tag_id', $tag->id );
		});
	}

	/**
	 * Filter model to subset with the given tags
	 *
	 * @param  object       $query
	 * @param  array|string $tags
	 * @return object       $query
	 */
	public function scopeWithTags( $query, $tags )
	{
		$tags = $this->makeTagsArray($tags);

		foreach ( $tags as $tag ) {
			$this->scopeWithTag($query, $tag);
		}

		return $query;
	}

	/**
	 * Filter model to subset with the given tags
	 *
	 * @param  object       $query
	 * @param  array|string $tags
	 * @return object       $query
	 */
	public function scopeWithAnyTag( $query, $tags )
	{
		$tags = $this->makeTagsArray($tags);

		$tag_ids = Tag::whereIn('tag', $tags)
			->lists('id');

		$taggable_ids = Taggable::whereIn('tag_id', $tag_ids)
			->where('taggable_type', $query->getModel()->getMorphClass())
			->lists('taggable_id');

		return $query->whereIn($this->getTable() .'.id', $taggable_ids);
	}

}
<?php namespace vendocrat\Tags;

use vendocrat\Tags\Models\Tag;

trait TaggableTrait
{
	/**
	 * Get all tags for this model.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
	 */
	public function tags()
	{
		return $this->morphToMany('vendocrat\Tags\Models\Tag', 'taggable');
	}

	/**
	 * Return collection of tagged rows related to the tagged model
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function tagged()
	{
		return $this->morphMany('vendocrat\Tags\Models\Tagged', 'taggable');
	}

	/**
	 * Sync tag relation adding new tags as needed
	 *
	 * @param array $tags
	 */
	public function tag( $tags )
	{
		$tags = $this->makeTagsArray($tags);

		$this->addTags($tags);

		if ( count($tags) > 0 ) {
			$this->tags()->sync(
				Tag::whereIn( 'tag', $tags )->lists('id')->all()
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
	 * @param $tags
	 * @return $this
	 */
	public function retag( $tags ) {
		return $this->detag()->tag($tags);
	}

	/**
	 * Removes all existing tags from model
	 *
	 * @return $this
	 */
	public function detag() {
		$this->removeAllTags();

		return $this;
	}

	/**
	 * @param $tag
	 * @return mixed
	 */
	public function hasTag( $tag )
	{
		return $this->tags->contains( 'tag', $tag );
	}

	/**
	 * @return mixed
	 */
	public function getTags()
	{
		return $this->tags->lists( 'tag' );
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
			Tag::create([
				'tag' => $tag
			]);
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
	 * @param string|array $tags
	 * @return array
	 */
	public static function makeTagsArray( $tags ) {
		if ( is_array($tags) ) {
			return $tags;
		} else if ( is_string($tags) ) {
			return preg_split('#[' . preg_quote( ',', '#' ) . ']#', $tags, null, PREG_SPLIT_NO_EMPTY);
		}

		return (array) $tags;
	}

	/**
	 * Filter model to subset with the given tags
	 *
	 * @param object $query
	 * @param $tags array|string
	 * @return object $query
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
	 * @param $query
	 * @param $tag string
	 * @return
	 */
	public function scopeWithTag( $query, $tag )
	{
		$tag = Tag::where('tag','=',$tag)->first();

		return $query->whereHas('tagged', function($q) use($tag) {
			$q->where( 'tag_id', '=', $tag->id );
		});
	}

}
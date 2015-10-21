<?php namespace vendocrat\Tags\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 * @package vendocrat\Tags\Models
 */
class Tag extends Model implements
	SluggableInterface
{
	use SluggableTrait;

	/**
	 * @inheritdoc
	 */
    protected $table = 'tags';

	/**
	 * @inheritdoc
	 */
    protected $fillable = [
		'tag',
		'slug'
	];

	/**
	 * @inheritdoc
	 */
    protected $validationRules = [
        'tag'  => 'required',
        'slug' => 'required',
    ];

	/**
	 * @inheritdoc
	 */
	protected $sluggable = [
		'build_from' => 'tag',
		'save_to'    => 'slug',
	];

	/**
	 * Get the Display Name
	 *
	 * @param int $limit
	 * @return string
	 */
	public function getDisplayName( $limit = 0 )
	{
		return $limit > 0 ? str_limit($this->tag, $limit) : $this->tag;
	}

	/**
	 * @param $query
	 * @param string $searchTerm
	 * @return mixed
	 */
	public function scopeSearch( $query, $searchTerm ) {
		return $query->where( 'tag', 'like', '%'. $searchTerm .'%' );
	}
}
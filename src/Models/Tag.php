<?php namespace vendocrat\Tags\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model implements
	SluggableInterface
{

	use SluggableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'tags';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable = [
		'tag',
		'slug'
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [];

	/**
	 * The validation rules for this model.
	 *
	 * @var array
	 */
    protected $validationRules = [
        'tag'  => 'required',
        'slug' => 'required',
    ];

	/**
	 * Sluggable
	 *
	 * @var array
	 */
	protected $sluggable = [
		'build_from' => 'tag',
		'save_to'    => 'slug',
	];

	/**
	 * @param $query
	 * @param string $searchTerm
	 * @return mixed
	 */
	public function scopeSearch( $query, $searchTerm ) {
		return $query->where( 'tag', 'like', '%'. $searchTerm .'%' );
	}

}
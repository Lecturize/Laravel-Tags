<?php namespace vendocrat\Tags\Models;

use Illuminate\Database\Eloquent\Model;

class Tagged extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'taggables';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'tag_id',
		'taggable_id',
		'taggable_type',
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

	public function taggable()
	{
		return $this->morphTo();
	}

	public function tag()
	{
		return $this->belongsTo('vendocrat\Tags\Models\Tag', 'tag_id', 'id');
	}

}
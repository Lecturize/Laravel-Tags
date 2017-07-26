<?php namespace Lecturize\Tags\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 * @package Lecturize\Tags\Models
 */
class Tag extends Model
{
	use Sluggable;

    /**
     * @inheritdoc
     */
    protected $table;

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
    public function __construct()
    {
        parent::__construct();

        $this->table = config('lecturize.tags.table', 'tags');
    }

	/**
	 * Taggify a given string
	 *
	 * @param  string $tag
	 * @return string
	 */
	public static function taggify( $tag ) {
		$filters = [
			' der ' => '',
			' des ' => '',
			' die ' => '',
			' und ' => '',
			' in '  => '',
/*
			'Ä' => 'AE',
			'Ö' => 'OE',
			'Ü' => 'UE',
			'ß' => 'ss',
			'ä' => 'ae',
			'ö' => 'oe',
			'ü' => 'ue',
*/
			'&' => '',
			'@' => '',
			'/' => '',
		];
		$tag = trim(str_replace(array_keys($filters), array_values($filters), $tag));

		$filters = [
			'/\(([^)]+)\)/' => '',
		];
		$tag = trim(preg_replace(array_keys($filters), array_values($filters), $tag));

		$tag = camel_case($tag);
		$tag = ucfirst($tag);
		$tag = trim($tag);

		return $tag;
	}

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

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'tag'
            ]
        ];
    }
}
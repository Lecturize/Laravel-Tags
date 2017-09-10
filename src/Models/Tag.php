<?php namespace Lecturize\Tags\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tag
 * @package Lecturize\Tags\Models
 */
class Tag extends Model
{
    use Sluggable;
    use SoftDeletes;

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
    protected $dates = ['deleted_at'];

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
     * @param  string  $tag
     * @return string
     */
    public static function taggify($tag)
    {
        $filters = config('lecturize.tags.filters', []);
        $tag = trim(str_replace(array_keys($filters), array_values($filters), $tag));

        $patterns = config('lecturize.tags.patterns', ['/\(([^)]+)\)/' => '']);
        $tag = trim(preg_replace(array_keys($patterns), array_values($patterns), $tag));

        if (config('lecturize.tags.camel_case', false)) {
            $tag = camel_case($tag);
            $tag = ucfirst($tag);
        }

        $tag = trim($tag);

        return $tag;
    }

    /**
     * Get the display name.
     *
     * @param  int  $limit
     * @return string
     */
    public function getDisplayName($limit = 0)
    {
        return $limit > 0 ? str_limit($this->tag, $limit) : $this->tag;
    }

    /**
     * Simple tag search.
     *
     * @param  $query
     * @param  string  $searchTerm
     * @return mixed
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('tag', 'like', '%'. $searchTerm .'%');
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
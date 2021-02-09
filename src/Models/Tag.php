<?php namespace Lecturize\Tags\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

use Cviebrock\EloquentSluggable\Sluggable;

/**
 * Class Tag
 * @package Lecturize\Tags\Models
 */
class Tag extends Model
{
    use Sluggable;
    use SoftDeletes;

    /** @inheritdoc */
    protected $fillable = [
        'tag',
        'slug'
    ];

    /**
     * The validation rules for this model.
     *
     * @return array
     */
    protected $validationRules = [
        'tag'  => 'required',
        'slug' => 'required',
    ];

    /** @inheritdoc */
    protected $dates = [
        'deleted_at'
    ];

    /** @inheritdoc */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

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
            $tag = Str::camel($tag);
            $tag = Str::ucfirst($tag);
        }

        return trim($tag);
    }

    /**
     * Get the display name.
     *
     * @param  int  $limit
     * @return string
     */
    public function getDisplayName($limit = 0)
    {
        return $limit > 0 ? Str::limit($this->tag, $limit) : $this->tag;
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

    /** @inheritdoc */
    public function sluggable(): array {
        return ['slug' => ['source' => 'tag']];
    }
}
<?php

namespace Lecturize\Tags\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

use Cviebrock\EloquentSluggable\Sluggable;

/**
 * Class Tag
 * @package Lecturize\Tags\Models
 * @property string       $tag
 * @property string|null  $slug
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

    /** @inheritdoc */
    protected $casts = [
        'deleted_at' => 'datetime'
    ];

    /**
     * The validation rules for this model.
     *
     * @var array
     */
    protected array $validationRules = [
        'tag'  => 'required',
        'slug' => 'required',
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
    public static function taggify(string $tag): string
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
    public function getDisplayName(int $limit = 0): string
    {
        return $limit > 0 ? Str::limit($this->tag, $limit) : $this->tag;
    }

    /**
     * Simple tag search.
     *
     * @param  Builder  $query
     * @param  string   $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('tag', 'like', '%'. $search .'%');
    }

    /** @inheritdoc */
    public function sluggable(): array {
        return ['slug' => ['source' => 'tag']];
    }
}
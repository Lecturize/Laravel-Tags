<?php namespace Lecturize\Tags\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Taggable
 * @package Lecturize\Tags\Models
 */
class Taggable extends Model
{
    /** @inheritdoc */
    protected $fillable = [
        'tag_id',
        'taggable_id',
        'taggable_type',
    ];

    /** @inheritdoc */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('lecturize.tags.table_pivot', 'taggables');
    }

    /**
     * Get the tagged item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function taggable()
    {
        return $this->morphTo();
    }

    /**
     * Get the tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'id');
    }
}
<?php

namespace Lecturize\Tags\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
     * @return MorphTo
     */
    public function taggable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the tag.
     *
     * @return BelongsTo
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'id');
    }
}
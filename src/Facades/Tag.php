<?php namespace Lecturize\Tags\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Tag
 * @package Lecturize\Tags\Facades
 */
class Tag extends Facade
{
    /** @inheritdoc */
    protected static function getFacadeAccessor(): string {
        return 'tags';
    }
}
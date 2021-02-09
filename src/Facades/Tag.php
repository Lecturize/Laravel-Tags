<?php namespace Lecturize\Tags\Facades;

use Illuminate\Support\Facades\Facade;

class Tag extends Facade
{
    /** @inheritdoc */
    protected static function getFacadeAccessor(): string {
        return 'tags';
    }
}
<?php namespace vendocrat\Tags\Facades;

use Illuminate\Support\Facades\Facade;
use vendocrat\Tags\Tags;

class Tag extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return Tags::class;
	}
}
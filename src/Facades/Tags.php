<?php namespace vendocrat\Tags\Facades;

use Illuminate\Support\Facades\Facade;

class Tags extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return \vendocrat\Tags\Tags::class;
	}
}
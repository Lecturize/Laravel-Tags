<?php namespace vendocrat\Tags;

use Illuminate\Support\ServiceProvider;

class TagsServiceProvider extends ServiceProvider
{
	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ .'/../config/config.php' => config_path('tags.php')
		], 'config');

		$this->publishes([
			__DIR__ .'/../database/migrations/' => database_path('migrations')
		], 'migrations');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ .'/../config/config.php',
			'tags'
		);

		$this->app->singleton(Tags::class, function ($app) {
			return new Tags($app);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return string[]
	 */
	public function provides()
	{
		return [
			Tags::class
		];
	}
}

[![Latest Stable Version](https://poser.pugx.org/vendocrat/laravel-tags/v/stable)](https://packagist.org/packages/vendocrat/laravel-tags)
[![Total Downloads](https://poser.pugx.org/vendocrat/laravel-tags/downloads)](https://packagist.org/packages/vendocrat/laravel-tags)
[![License](https://poser.pugx.org/vendocrat/laravel-tags/license)](https://packagist.org/packages/vendocrat/laravel-tags)

# Laravel Tags

## Installation

Require the package from your `composer.json` file

```php
"require": {
	"vendocrat/laravel-tags": "dev-master"
}
```

and run `$ composer update` or both in one with `$ composer require vendocrat/laravel-tags`.

Next register the service provider and (optional) facade to your `config/app.php` file

```php
'providers' => [
    // Illuminate Providers ...
    // App Providers ...
    vendocrat\Tags\TagsServiceProvider::class
];
```

```php
'providers' => [
	// Illuminate Facades ...
    'Tag' => vendocrat\Tags\Facades\Tags::class
];
```

## Configuration & Migration

```bash
$ php artisan vendor:publish --provider="vendocrat\Tags\TagsServiceProvider"
```

This will create a `config/tags.php` and a migration file. In the config file you can customize the table names, finally you'll have to run migration like so:

```bash
$ php artisan migrate
```

## License

Licensed under [MIT license](http://opensource.org/licenses/MIT).

## Author

**Handcrafted with love by [Alexander Manfred Poellmann](http://twitter.com/AMPoellmann) for [vendocrat](https://vendocr.at) in Vienna &amp; Rome.**
[![Latest Stable Version](https://poser.pugx.org/lecturize/laravel-tags/v/stable)](https://packagist.org/packages/lecturize/laravel-tags)
[![Total Downloads](https://poser.pugx.org/lecturize/laravel-tags/downloads)](https://packagist.org/packages/lecturize/laravel-tags)
[![License](https://poser.pugx.org/lecturize/laravel-tags/license)](https://packagist.org/packages/lecturize/laravel-tags)

# Laravel Tags

Simple way to tag eloquent models in Laravel.

## Installation

Require the package from your `composer.json` file

```php
"require": {
    "lecturize/laravel-tags": "^1.0"
}
```

and run `$ composer update` or both in one with `$ composer require lecturize/laravel-tags`.

## Configuration & Migration

```bash
$ php artisan vendor:publish --provider="Cviebrock\EloquentSluggable\ServiceProvider"
$ php artisan vendor:publish --provider="Lecturize\Tags\TagsServiceProvider"
```

This will publish a `config/sluggable.php`, a `config/lecturize.php` and some migration files, that you'll have to run:

```bash
$ php artisan migrate
```

For migrations to be properly published ensure that you have added the directory `database/migrations` to the classmap in your projects `composer.json`.

## Usage

First, add our `HasTags` trait to your model.
        
```php
<?php namespace App\Models;

use Lecturize\Tags\Traits\HasTags;

class Post extends Model
{
    use HasTags;

    // ...
}
?>
```

##### Get all tags for a model
```php
$tags = $model->tags();
```

##### Check if model is tagged with a given tag
```php
$tags = $model->hasTag('Check');
```

##### Add one or more tags
```php
$tags = $model->tag('My First Tag');
$tags = $model->tag(['First', 'Second', 'Third']);
```

##### Remove given tags from model
```php
$tags = $model->untag('Remove');
```

##### Replace all existing tags for a model with new ones
```php
$tags = $model->retag('A New Tag');
$tags = $model->retag(['Fourth', 'Fifth', 'Sixth']);
```

##### Remove all existing tags from model
```php
$tags = $model->detag();
```

##### Get a list of all tags
```php
$tags = $model->listTags();
```

This is a convenience method for `$model->tags->pluck('tag')`

##### Scopes

There are two scopes included to fluently query models (e.g. Posts) with given tags.

```php
$posts = Post::withTag('My First Tag')->get();
$posts = Post::withTags(['First', 'Second', 'Third'])->get();
```

## License

Licensed under [MIT license](http://opensource.org/licenses/MIT).

## Author

**Handcrafted with love by [Alexander Manfred Poellmann](https://twitter.com/AMPoellmann) in Vienna &amp; Rome.**
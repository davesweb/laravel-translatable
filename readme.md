# Laravel Translatable

Add translation to Laravel Models by adding a translation model and a translation database table.

## Installation

Via composer

```shell
composer require davesweb/laravel-translatable
```

## Configuration

- Add the `Davesweb\LaravelTranslatable\Traits\HasTranslations` trait to the model you want to translate.
- Create a translation model which holds the translatable attributes, but instead of extending the Laravel `Model`
  class, extend the `Davesweb\LaravelTranslatable\Models\TranslationModel` class.
- Create a migration to create the database table for your translation model.
  There are also commands available for generating these models and migrations for you.
  As long as the names of the translation model and the foreign key column follow the naming conventions, the
  package will automatically find the correct models and set the correct relations.
- The name of the translation model should be the same as the model that is being translated with the suffix
  `Translation`, for example `App\Page` and `App\PageTranslation`.
- The name of the foreign key column should be the name of the model that is being translated in snake case,
  suffixed with the name of the primary key. For instance `page_id`.
  If your model names differ from te naming convention you can specify them yourself on the models. For the
  model to be translated, add a `$translation` property.
  
  ```php
  <?php
  
  namespace App;
  
  use Illuminate\Database\Eloquent\Model;
  
  class Page extends Model
  {
      protected string $translation = App\DifferentPageTranslationName::class;
  }
  ```
  
  For translation models you can set the `translates` property.

  ```php
  <?php
  
  namespace App;
  
  use Davesweb\LaravelTranslatable\Models\TranslationModel;
  
  class PageTranslation extends TranslationModel
  {
      protected string $translates = App\SomeOtherModel::class;
  }
  ```

## Docker

This package has a Docker setup for easy development. To use it, simply copy the `docker-compose.yaml.dist` file 
to `docker-compose.yaml` and adjust anything you'd want to adjust.

```shell
cp docker-compose.yaml.dist docker-compose.yaml
```

Then up the container:

```shell
docker-compose up -d
```

Now you can log in to the container:

```shell
docker-compose exec app bash
```

## Usage

Because the translation are just a one-to-many relation of the model you can use them the same way as any other 
one-to-many relationship in Laravel. There are a few helper methods to make it easier to use the package, but the base 
is just a relation.

### Fetching translations

You can get the collection of all translations from a model by calling `getTranslations()`.

```php
<?php

$page = App\Page::query()->findOrFail(1);

$translations = $page->getTranslations();
```

In order to optimize your database calls you should eager-load your translations:

```php
<?php

$page = App\Page::query()->with('translations')->findOrFail(1);

$translations = $page->getTranslations();
```

Or load only the translation in the locale you want:

```php
<?php

use Illuminate\Database\Eloquent\Builder;

$locale = 'nl';

$page = App\Page::query()->whereHas('translations', function(Builder $query) use ($locale) {
    $query->where('locale', '=', $locale);
})->findOrFail(1);

$translations = $page->getTranslations();
```

You can fetch a single translation by calling `getTranslation('locale')`.

```php
<?php
$page = App\Page::query()->with('translations')->findOrFail(1);

$englishTranslation = $page->getTranslation('en');
$dutchTranslation   = $page->getTranslation('nl');
```

To get the translation in the current locale the app is in you can call `translation()`.

```php
<?php

app()->setLocale('en');

$page = App\Page::query()->with('translations')->findOrFail(1);

$englishTranslation = $page->translation();
```

To get the translation of a translated attribute in the current locale the app is in you can call `translate('attribute')`.

```php
<?php

app()->setLocale('en');

$page = App\Page::query()->with('translations')->findOrFail(1);

$englishTitle = $page->translate('title');
```

To get the translation of a translated attribute in a different locale then the current app locale is in you can call 
`translate('attribute')` with the desired locale.

```php
<?php

app()->setLocale('en');

$page = App\Page::query()->with('translations')->findOrFail(1);

$dutchTitle = $page->translate('title', 'nl');
```

### Saving translations

Saving a translation is done just like any other Laravel Model. Set the attributes on the TranslationModel in your 
preferred way, then save it to the model by calling `$page->translations()->save($pageTranslation);`

## Artisan commands

There are three command available for this package:

```shell
php artisan make:translatable {name}
```

This command creates a new Model class with the given {name} and the `HasTranslations` trait already set.
This command has a few options as well:

- `-t` or `--translation`: Adding this will also make a TranslationModel class for the model.
- `-m` or `--migration`: Adding this will also make a migration for both the model and the TranslationModel
- `-a` or `-all`: Use all available options.

```shell
php artisan make:translation {name}
```

This command creates a new TranslationModel class with the given {name}.

```shell
php artisan make:translatable-migration {name}
```

This command creates a new migration with both the model and translation model migrations for the given {name}. The 
{name} should be the classname of the model, for instance `App\Page`.

## Testing

To run the testsuite, simply run

```shell
docker-compose exec app composer test
```

## Code style

This package uses PHP CS Fixer to enforce code style. To run it, run 

```shell
docker-compose exec app composer cs-fixer
```

## License

This package is licensed under the MIT license, which basically means you can do whatever your want with this package. 
However, if you found this package useful, please consider buying me a beer or subscribing to premium email support 
over on [Patreon](https://www.patreon.com/davesweb), it's really appreciated!
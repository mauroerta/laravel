# Laravel

Some commands and traits.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

Install Laravel: [Laravel](https://laravel.com/)

```
laravel new my-project
cd my-project
```

### Installing

Install mauroerta/laravel

```
cd path/to/your/laravel/project
composer require mauroerta/laravel
```

And then publish the configuration file and the migrations, run:

```
php artisan vendor:publish
```

and follow the instructions.

## Commands

Now you have 2 new commands:

```
php artisan make:trait
php artisan make:observer Name --observe=App\\Class\\To\\Observe
```
When you use the make:observer command you have to update the AppServiceProvider.php file:
Example of UserObserver:

```
php artisan make:observer UserObserver --observe=App\\User
```

```
// ...
use App\Observers\UserObserver;
// ...
public function boot()
{
    // ...
    App\User::observe(UserObserve::class);
}
```

## Traits

Linkable Trait, Slugable Trait, Draftable Trait.
Description soon.

## Authors

* **Mauro Erta** - [ME](https://github.com/mauroerta)

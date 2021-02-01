# Roadmap

## Create a project structure

```sh
composer create-project --prefer-dist laravel/laravel Socialite
```

## Database setup

Open the .env file, and update your database configurations.

```dotenv
DB_HOST=mysql
```

## Start Application with Sail

```sh
sail up -d
```

## Install Laravel Socialite

```sh
sail composer require laravel/socialite
```

-   Inside `config/services.php` add the content below:

```php

'graph' => [
    'client_id'     => env('GRAPH_CLIENT_ID'),
    'client_secret' => env('GRAPH_CLIENT_SECRET'),
    'redirect'      => env('GRAPH_REDIRECT_CALLBACK'),
],
```

- add the content below to `.env`

```environment
# O365 / MICROSOFT AZURE AD LOGIN

GRAPH_CLIENT_ID=xxxx
GRAPH_CLIENT_SECRET=yyyyy
GRAPH_REDIRECT_CALLBACK=http://localhost/callback
```

## Database migrations

```sh
sail artisan migrate
```

## @todo:

- Continue the saga :wink:

## References

-   [Laravel Socialite](https://laravel.com/docs/8.x/socialite)
-   [Laravel Socialite - Office 365](https://stackoverflow.com/questions/37929579/laravel-socialite-and-office365-invalidargumentexception-in-manager-php-line-90)

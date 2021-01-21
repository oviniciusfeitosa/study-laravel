# Roadmap

## Create app structure

```shell
curl -s https://laravel.build/Breeze | bash
```

## Start your Stack

```shell
sail up -d
```

## Add Breeze dependency

```shell
sail composer require laravel/breeze --dev
```

## Install Breeze

Publish authentication views, routes, controllers, and other resources. 
Laravel Breeze publishes all of its code to your application so that you have full control and visibility over its features and implementation. 
After Breeze is installed, you should also compile your assets so that your application's CSS file is available.

```shell
sail artisan breeze:install 
npm install && npm run dev
```

## Migrations

- Run the migrations

```shell
sail artisan migrate
```

## Login and register routes

> Next, you may navigate to your application's `/login` or `/register` URLs in your web browser. 
> All of Breeze's routes are defined within the `routes/auth.php` file.

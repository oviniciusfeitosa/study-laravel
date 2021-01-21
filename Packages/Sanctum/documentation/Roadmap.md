# Roadmap

## Create app structure

```shell
curl -s https://laravel.build/Sanctum | bash
```

## Start your Stack

```shell
./vendor/bin/sail up -d
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
sail npm install && sail npm run dev
```

## Migrations

- Run the migrations

```shell
sail artisan migrate
```

## Login and register routes

> Next, you may navigate to your application's `/login` or `/register` URLs in your web browser. 
> All of Breeze's routes are defined within the `routes/auth.php` file.

## Implement e-mail verification

- Implement `MustVerifyEmail` contract in `User.php`

```php
class User extends Authenticatable implements MustVerifyEmail
...
```

- Add Sender e-mail inside the `.env` file

```dotenv
MAIL_FROM_ADDRESS=admin-jetstream@admin-jetstream.com
```

- Add routes for verify verification inside the `routes/web.php` file

```php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

...

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
```

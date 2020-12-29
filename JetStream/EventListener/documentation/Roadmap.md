# Roadmap

## Create app structure

```shell
laravel new EventListener --jet

# Choose: 0 - Livewire
```

## Start your Stack

```shell
sail up -d
```

## Add Breeze dependency

```shell
composer require laravel/breeze --dev
npm install
npm run dev
```

## Configure your ServiceProvider

```php
# AppServiceProvider

use Illuminate\Support\Facades\Schema;

public function boot()
{
    Schema::defaultStringLength(191);
}
```

## Change database host

- Open the `.env` file and set the host for mysql.

```dotenv
...
DB_HOST=mysql
...
```

## Run the migrations

```sh
sail artisan migrate
```

### Working around

- Remove application cache 

```shell
docker rm -f eventlistener_mysql_1
docker volume rm eventlistener_sailmysql

sail artisan key:generate
sail artisan cache:clear
sail artisan config:clear
```

- Change database host inside the `.env` file

```dotenv
DB_HOST=mysql
```

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

## References

- https://medium.com/dev-genius/setting-up-laravel-8-x-with-jetstream-auth-84bbeafc0cd3

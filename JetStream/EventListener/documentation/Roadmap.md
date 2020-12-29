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

## References

- https://medium.com/dev-genius/setting-up-laravel-8-x-with-jetstream-auth-84bbeafc0cd3

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

## Link Users with Social Identities

```sh
sail artisan make:model SocialIdentity -m
```

- Add the snippet below to app/User.php to indicate that a user has many Social Identities:

```php
# app/User.php

public function identities() {
   return $this->hasMany('App\SocialIdentity');
}
```

- Similarly, edit `app/SocialIdentity.php` to indicate that a social identity belongs to a user:

```php
# app/SocialIdentity.php

class SocialIdentity extends Model
{
    protected $fillable = ['user_id', 'provider_name', 'provider_id'];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
```

- Change the `up` method for the `create_social_identities_table` migration to this:

```php
# database/migrations/[timestamp]_create_social_identities_table.php

public function up()
{
    Schema::create('social_identities', function (Blueprint $table) {
        $table->increments('id');
        $table->bigInteger('user_id');           
        $table->string('provider_name')->nullable();
        $table->string('provider_id')->unique()->nullable();          
        $table->timestamps();
    });
}
```

- Now we are all set to run our migrations and then get to the fun bit; setting up socialite. On the command line type

```sh
php artisan migrate
```

## Routing

```sh
# routes/web.php

# redirecting the user to the OAuth provider
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
# receiving the callback from the provider after authentication
Route::get('login/{provider}/callback','Auth\LoginController@handleProviderCallback');
```

- Our next step is updating the Login controller with the methods necessary for redirecting users to the OAuth providers and handling the provider callbacks. We will access Socialite using the Socialite facade

```php
# app/Http/Controllers/Auth/LoginController.php
[...]
use Auth;
use Socialite;
Use App\User;
use App\SocialIdentity;
[...]
class LoginController extends Controller

{
   public function redirectToProvider($provider)
   {
       return Socialite::driver($provider)->redirect();
   }

   public function handleProviderCallback($provider)
   {
       try {
           $user = Socialite::driver($provider)->user();
       } catch (Exception $e) {
           return redirect('/login');
       }

       $authUser = $this->findOrCreateUser($user, $provider);
       Auth::login($authUser, true);
       return redirect($this->redirectTo);
   }


   public function findOrCreateUser($providerUser, $provider)
   {
       $account = SocialIdentity::whereProviderName($provider)
                  ->whereProviderId($providerUser->getId())
                  ->first();

       if ($account) {
           return $account->user;
       } else {
           $user = User::whereEmail($providerUser->getEmail())->first();

           if (! $user) {
               $user = User::create([
                   'email' => $providerUser->getEmail(),
                   'name'  => $providerUser->getName(),
               ]);
           }

           $user->identities()->create([
               'provider_id'   => $providerUser->getId(),
               'provider_name' => $provider,
           ]);

           return $user;
       }
   }
}
```

- Change permissions

```sh
sudo chmod 777 -R /home/vinnyfs89/www/vinnyfs89/study-laravel/OnlyAPI/Socialite/storage
```

## References

-   [Laravel Socialite](https://laravel.com/docs/8.x/socialite)
-   [Laravel Socialite - Office 365](https://stackoverflow.com/questions/37929579/laravel-socialite-and-office365-invalidargumentexception-in-manager-php-line-90)

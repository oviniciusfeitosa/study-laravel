# Roadmap

## Create a project structure

```sh
composer create-project --prefer-dist laravel/laravel AuthRole
```

## Database setup

Open the .env file, and update your database configurations.

```dotenv
DB_HOST=mysql
```

## Install Laravel Passport

```sh
composer require laravel/passport
```

-   Inside `app/Providers/AppServiceProvider.php` add this to the `boot` function

```php
use Illuminate\Support\Facades\Schema;

...
Schema::defaultstringLength(191);
```

## Start Application with Sail

```sh
sail up -d
```

## Database migrations

```sh
sail artisan migrate
```

## Create the encryption keys

```sh
sail artisan passport:purge
sail artisan passport:install --force
```

## Add the HasApiTokens trait to our user model

-   Go to `App\Models\User.php` and use `HasApiToken` from `Laravel\Passport\HasApiTokens`

## Call the passport routes in AuthServiceProvider

-   Go to `App/Providers/AuthServiceProvider.php` and add `Passport::routes();` from `Laravel\Passport\Passport`

-   Uncomment the $policies contents

    ```php
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];
    ```

## Set the driver

-   Go to `config\auth.php`, locate the **guards** array and change the driver from token to passport

```php
...
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
            'hash' => false,
        ],
    ],
...
```

## Create the Migration file for our CRUD api project

```shell
php artisan make:model Project -m
```

-   A migration file will be created in the database/migrations folder named `CreateProjectsTable`. Change your content as bellow:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('introduction', 500)->nullable();
            $table->string('location', 255)->nullable();
            $table->integer('cost')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
```

-   A model file will be created in the `app/Models` folder named `Project`. Change your content as bellow:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'location',
        'introduction',
        'cost',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'cost' => 'int',
    ];
}
```

## Migrate the new table

```sh
sail artisan migrate
```

## Create a Resource

```sh
sail artisan make:resource ProjectResource
```

## Create AuthController

```sh
sail artisan make:controller API/AuthController
```

-   Fill the `AuthController` with content below:

```php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = Hash::make($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken], 201);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'This User does not exist, check your details'], 400);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }
}
```

## ProjectController - Create only api structure

```shell
sail artisan make:controller API/ProjectController --api --model=Project
```

> Note: **--api** flag means **only RESTful methods** will be generated.

-   Fill the `ProjectController` with content bellow:

```shell
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();
        return response([ 'projects' => ProjectResource::collection($projects), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'cost' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $project = Project::create($data);

        return response(['project' => new ProjectResource($project), 'message' => 'Created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return response(['project' => new ProjectResource($project), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $project->update($request->all());

        return response(['project' => new ProjectResource($project), 'message' => 'Update successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response(['message' => 'Deleted']);
    }
}
```

## API router registration

-   Inside `routes/api.php` add these lines:

```php
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProjectController;

...

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::apiResource('projects', ProjectController::class)->middleware('auth:api');
```

## References

-   [How to Create a Secure CRUD RESTful API in Laravel 8 and 7 Using Laravel Passport](https://dev.to/kingsconsult/how-to-create-a-secure-crud-restful-api-in-laravel-8-and-7-using-laravel-passport-31fh)

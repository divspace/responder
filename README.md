# Responder

A **Laravel 4** wrapper for [League/Fractal](http://fractal.thephpleague.com) to handle API responses. For **Laravel 5**, please [go here](https://github.com/salebab/larasponse/tree/L5).

## Installation

Add **Responder** to your `composer.json` file by running the following command:

```sh
$ composer require divspace/responder
```

Then run the following command:

```sh
$ composer update
```

### Register Package

Add the following line to the end of the `providers` array in `app/config/app.php`:

```php
'providers' => array(
    // ...
    'Divspace\Responder\ResponderServiceProvider'
)
```

## Usage

There are three types of responses you can return:

1. Item
2. Collection
3. Paginated Collection

Here are examples of all three types in a single controller:

```php
use Divspace\Responder\Responder;
use Data\Transformers\UserTransformer;

class UserController extends BaseController {
    public $response;

    public function __construct(Responder $response) {
        $this->response = $response;

        /**
         * Fractal parseIncludes() method
         * http://fractal.thephpleague.com/transformers/
         */
        if(isset($_GET['include'])) {
            $this->response->parseIncludes($_GET['include']);
        }
    }

    /**
     * Display a paginated list of users
     */
    public function index() {
        return $this->response->paginatedCollection(User::paginate());
    }

    /**
     * Display a single user
     */
    public function item($id) {
        return $this->response->item(User::find($id), new UserTransformer());
    }

    /**
     * Display all users
     */
    public function collection() {
        return $this->response->collection(User::all(), new UserTransformer());
    }
}
````

The `Data\Transformers\UserTransformers` file might look something like this (with an example of how to use the `parseIncludes()` method from the previous example):

```php
<?php namespace Data\Transformers;

use User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'user_type'
    ];

    public function transform(User $user) {
        $user = User::find($user->id);

        return [
            'id'         => (int) $user->id,
            'email'      => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }

    public function includeUserType(User $user) {
        $userType = $user->type;

        return $this->item($userType, new UserTypeTransformer);
    }

}
````

Since we've used the `parseIncludes()` method in the previous example, we need to create that file as well so we can return more information in our API response:

````php
<?php namespace Data\Transformers;

use User;
use League\Fractal\TransformerAbstract;

class UserTypeTransformer extends TransformerAbstract {

    public function transform($userType) {
        if(count($userType) > 0) {
            return [
                'id'   => (int) $userType->id,
                'name' => $userType->name
            ];
        }

        return [];
    }

}
````

You'll want to refer to the full [Fractal documentation](http://fractal.thephpleague.com) to get an idea of everything works together.

## Credit

This is modified fork of [Larasponse](https://github.com/salebab/larasponse) for **Laravel 4.2** that uses [Fractal](http://fractal.thephpleague.com) as the default provider for API responses.
# Repository pattern for Laravel 5

Simple implementation of the repository pattern for Laravel 5.

## Install

Pull this package in through Composer.

```js
{
  "require": {
    "bican/repository": "0.9"
  }
}
```

## Usage

You need to create a folder for your repositories inside app directory. 

Then you can create your first repository. There is an example for default User model.

```php
use Bican\Repository\Repository;

class UserRepository extends Repository {}
```

Now you can inject this repository wherever you want and have access to methods from abstract class Repository. This package is smart enough to find a specific model if you name your repository correct. For model Article you need to create ArticleRepository and everything will work just fine.

If you want to overwrite default model, you can do it like this:

```php
use Bican\Repository\Repository;

class ArticleRepository extends Repository {
  
  $modelName = 'App\Post';
  
}
```

Models are inside App directory in Laravel 5. If you want to move them inside another folder (for example Models), you need to also specify it.

```php
use Bican\Repository\Repository;

class UserRepository extends Repository {
  
  $modelFolder = 'Models';
  
}
```

You are now set to go. Look at RepositoryInterface.php to find out what methods you can use and what parametr you need to pass. You can overwrite them in your child repository or you can create new ones. There is a small example:

```php
use Bican\Repository\Repository;

class ArticleRepository extends Repository {
  
  public function findAll(array $orderBy = ['id', 'asc'], array $columns = ['*'])
  {
    return $this->model->with('users')->orderBy(orderBy[0], $orderBy[1])->get($columns);
  }
  
}
```
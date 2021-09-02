## Installing

```shell
$ composer require really4you/laravel-optimistic
```

## Usage

Add `Really4you\LaravelOptimistic\Optimistic` trait to the model and set versionable attributes:

```php
use Really4you\laravelOptimistic\Optimistic;

class Post extends Model
{
    use Optimistic;
    
    /**
     * the name of the "optimistic" column.
     *
     * @var string
     */
    protected $versionAt = 'updating_column';
    
    <...>
}
```

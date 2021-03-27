# Resolver

A resolver follows a container pattern and is used throughout the framework to
manage dependancies within classes. The main difference between this and
containers found in other frameworks is that you can mount resolvers via traits.

```

use UGComponents\Resolver\ResolverTrait;

class MyClass
{
  use ResolverTrait;
}

```

Out of the box, resolvers can be used to instantiate classes like the following
example.

###### Instantiating a Class
```

use UGComponents\Event\EventHandler;

$handler = $this->resolve(EventHandler::class, ...$args);

```

If you wanted to make this into a static class, where it returns the same
instance every time we can do so like the following.

```

$this->addResolver(EventHandler::class, function(...$args) {
  static $class = null;

  if (is_null($class)) {
    $class = new EventHandler(...$args);
  }

  return $class;
});

```

```info
There's no need to setup a static class pattern for every class. `ResolverTrait`
also comes with a method that does just that.
```

###### Static Class Call
```

$this->resolveShared(EventHandler::class, ...$args);

```

What the example above also shows that there's nothing stopping us from out
right stubbing the `EventHandler` class. The following shows how this can be
done.

```

$this->addResolver(EventHandler::class, function(...$args) {
  return new EventHandlerStub(...$args);;
});

```

Resolvers can also resolve static methods like the following.

```
$this->resolveStatic(MyClass::class, 'aStaticMethod', ...$args);
$this->resolve('MyClass::aStaticMethod', ...$args);
```

In fact you can use Resolvers for anything that needs a resolution.

```

$this->addResolver('foo', function($number) {
  return 'bar' . $number;
});

$this->resolve('foo', 1); //--> 'bar1'

```

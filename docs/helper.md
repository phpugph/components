# Helper Traits
 - [Binder (1)](#binder)
 - [Conditional (1)](#condition)
 - [Loop (1)](#loop)
 - [Instance (1)](#instance)
 - [Singleton (1)](#singleton)

<a name="binder"></a>
## Binder

The binder trait is used to bind callbacks to the class where this trait is
mounted. Mounting this trait can be done with the following example.

```

use UGComponents\Helper\BinderTrait

class MyClass
{
  use BinderTrait;
}

```

The binder trait adds on just one method to your class called `bindCallback`.
This method gives the callback that is being passed access to all protected,
private and static relations defined in the class. The following shows how this
method can be used.

```

$this->bindCallback(function() {
  //I now have access to $this and self
});

```

<a name="condition"></a>
## Conditional

The conditional trait is used as an external interface to case for specific
things having to do with your class properties conditionally. Mounting this
trait can be done with the following example.

```

use UGComponents\Helper\ConditionalTrait

class MyClass
{
  use ConditionalTrait;
}

```

The conditional trait adds on just one method to your class called `when`.
This method has three parameters which is `$conditional`, `$success`, `$fail`
respectively.The following shows how this method can be used.

```

//case 1
$this->when(true, function() {
  //do something good
});

//case 2
$this->when(true, function() {
  //do something good
}, function() {
  //do something bad
});

//case 3
$this->when(function() {
  //am I good?
  return true;
},
function() {
  //do something good
},
function() {
  //do something bad
});

```

In all parameters as callbacks you have access to all protected,
private and static relations defined in the class.

```

//case 4
$this->when(function() {
  //I now have access to $this and self
},
function() {
  //I also now have access to $this and self
},
function() {
  //I also now have access to $this and self
});

```

<a name="loop"></a>
## Loop

The loop trait is used as an external interface to case for specific
things having to do with your class properties iteratively. Mounting this
trait can be done with the following example.

```

use UGComponents\Helper\LoopTrait

class MyClass
{
  use LoopTrait;
}

```

The loop trait adds on just one method to your class called `loop`.
This method gives the callback that is being passed access to all protected,
private and static relations defined in the class. The following shows how this
method can be used.

```

$this->loop(function($i) {
  //I still have access to $this and self

  if($i > 10) {
    return false;
  }

  return true;
});

```

In this example above, the callback will continue to loop as long as it
returns an equivalent to true. `$i` will be started at `0` and incremented
each time the callback is called. You can preset `$i` like the following
example.

```

$this->loop(function($i) {
  //I would never run.

  if($i > 10) {
    return false;
  }

  return true;
}, 11);

```

```warning
Not returning an equivalent false will make this loop infinite.
```

<a name="instance"></a>
## Instance

The instance trait is used as an external interface quickly instantiate your
class in a chainable manner. Mounting this trait can be done with the following example.

```

use UGComponents\Helper\InstanceTrait

class MyClass
{
  use InstanceTrait;
}

```

The instance trait adds on just one static method to your class called `i`,
which is short for `instance` or `getInstance` in other libraries. The
following shows how this method can be used.

```

MyClass::i()->arbitraryDefinedMethod();

```

You can also pass arguments to the constructor by passing it withing the `i`
method like the following.

```

MyClass::i($foo, $bar, 123)->arbitraryDefinedMethod();

```

<a name="singleton"></a>
## Singleton

The singleton trait has an exact usage case as the instance trait. The only
difference is that it only allows the class to be instantiated once. Mounting
this trait can be done with the following.

```

use UGComponents\Helper\SingletonTrait

class MyClass
{
  use SingletonTrait;
}

```

And it's usage just like the instance trait is the following

```

MyClass::i($foo, $bar, 123)->arbitraryDefinedMethod();

```

```info
Passing arguments to the class will only work the first time around, it's
otherwise ignored on multiple usages.
```

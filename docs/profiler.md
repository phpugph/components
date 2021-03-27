# Profiler Traits
 - [Caller (2)](#caller)
 - [Inspector (1)](#inspector)
 - [Logger (2)](#logger)

<a name="#caller"></a>
## Caller

The caller trait profiles meta information about the current method and the
method that called it. Mounting this trait can be done with the following
example.

```

use UGComponents\Profiler\CallerTrait

class MyClass
{
  use CallerTrait;
}

```

The caller trait adds on two method to your class called `getCaller` and
`getCallee`. The following shows how these method can be used.

```

$this->getCaller(); //--> ['file', 'line', 'class', 'method' ...]
$this->getCallee(); //--> ['file', 'line', 'class', 'method' ...]

```


<a name="#inspector"></a>
## Inspector

The inspector trait profiles class properties in certain stages. Mounting
this trait can be done with the following example.

```

use UGComponents\Profiler\InspectorTrait

class MyClass
{
  use InspectorTrait;
}

```

The inspector trait adds on just one method to your class called `inspect`
which can be used in various ways. The following shows how this method can be
used.

```

//echo MyClass->protectedProperty
$this->inspect('protectedProperty');

//echo MyClass->protectedProperty after `someMethod` was called
$this->inspect('protectedProperty', true)->someMethod();

```

<a name="#caller"></a>
## Logger

The logger trait enables a clean way to take notes. Mounting
this trait can be done with the following example.

```

use UGComponents\Profiler\LoggerTrait

class MyClass
{
  use LoggerTrait;
}

```

The logger trait adds on two method to your class called `addLogger` and `log`
which can be used in various ways. The following shows how these method can be
used.

```

$this->addLogger(function($message) {
  //Do something
});

$this->log('Something has happened');

```

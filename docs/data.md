# Data
 - [ArrayAccess Trait (4)](#arrayaccess)
 - [Countable Trait (1)](#countable)
 - [Iterator Trait (5)](#iterator)
 - [Magic Trait (4)](#magic)
 - [Dot Trait (4)](#dot)
 - [Generator Trait (1)](#generator)
 - [Data Trait](#data)
 - [Registry Object (5)](#registry)
 - [Model Object (2)](#model)
 - [Collection Object (6)](#collection)

This section is all about data management, all of which perform tasks on a
singular protected property called `$data`.

<a name="arrayaccess"></a>
## ArrayAccess Trait

The ArrayAccess trait wraps the ArrayAccess interface of PHP and when mounted
will allow your class to be accessed like an array. Mounting this trait can be
done with the following example.

```

use UGComponents\Data\ArrayAccessTrait

class MyClass
{
  use ArrayAccessTrait;

  protected $data = array();
}

```

The ArrayAccess trait implements the ArrayAccess interface exactly adding
`offsetSet`, `offsetGet`, `offsetExists`, `offsetUnset` methods to your class
which makes the following now possible.

```

$myClass = new MyClass;

if(!isset($myClass['foo'])) {
  $myClass['foo'] = 'bar';
}

echo $myClass['foo']; //--> 'bar'

unset($myClass['foo']);

```

<a name="countable"></a>
## Countable Trait

The Countable trait wraps the Countable interface of PHP and when mounted
will allow your class to be counted like an array. Mounting this trait can be
done with the following example.

```

use UGComponents\Data\CountableTrait

class MyClass
{
  use CountableTrait;
  protected $data = array();
}

```

The Countable trait implements the Countable interface exactly adding the
`count` method to your class which makes the following now possible.

```

$myClass = new MyClass;
echo count($myClass); //--> 0

```

<a name="iterator"></a>
## Iterator Trait

The Iterator trait wraps the Iterator interface of PHP and when mounted
will allow your class to be iterated like an array. Mounting this trait can be
done with the following example.

```

use UGComponents\Data\IteratorTrait

class MyClass
{
  use IteratorTrait;

  protected $data = array();
}

```

The Iterator trait implements the Iterator interface exactly adding
`rewind`, `current`, `next`, `key`, `valid` methods to your class
which makes the following now possible.

```

$myClass = new MyClass;

foreach($myClass as $key => $value) {
  //Do something
}

```

<a name="magic"></a>
## Magic Trait

The Magic trait adds extra accessors for the data array. Mounting this trait can be
done with the following example.

```

use UGComponents\Data\MagicTrait;

class MyClass
{
  use MagicTrait {
    MagicTrait::__getData as __get;
    MagicTrait::__setData as __set;
    MagicTrait::__callData as __call;
    MagicTrait::__toStringData as __toString;
  }

  protected $data = array();
}

```

The Magic trait implements three magic methods adding `__call`, `__get`,
`__set` methods to your class which makes the following now possible.

```info
We suffixed the magic methods with `'Data'` as in `__getData` to keep the
methods free for you to define as you can always alias them.
```

```

$myClass = new MyClass;

$myClass->setFooBar('zoo'); // $data['foo_bar']
echo $myClass->getFooBar(); //--> 'zoo'

$myClass->foo_bar = 'zoo'; // $data['foo_bar']
echo $myClass->foo_bar; //--> 'zoo'

echo $myClass; //--> <JSON string>

```

<a name="dot"></a>
## Dot Trait

The Dot trait adds accessors to help manage three or more dimensional arrays.
Mounting this trait can be done with the following example.

```

use UGComponents\Data\DotTrait;

class MyClass
{
  use DotTrait;

  protected $data = array();
}

```

The Dot trait implements a four methods called `getDot`, `setDot`,
`isDot`, `removeDot` which can be used like the following

```

$myClass = new MyClass;

$myClass->setDot('some.path.to.value', 'foobar'); // $data['some']['path']['to']['value'] = 'foobar'

$myClass->getDot('some.path.to.value'); //--> 'foobar'
$myClass->getDot('some.path.to'); //--> ['value' => 'foobar']

$myClass->removeDot('some.path');
$myClass->isDot('some.path'); //--> false

```

```info
All dot methods have an extra parameter where you can specify the delimeter.
```

```

$myClass->getDot('some-path-to-value', '-');

```

<a name="generator"></a>
## Generator Trait

The Generator trait takes advantage of PHP generators.
Mounting this trait can be done with the following example.

```

use UGComponents\Data\GeneratorTrait;

class MyClass
{
  use GeneratorTrait;

  protected $data = array();
}

```

The Generator trait implements a single method called `generator` which can be
used like the following.

```

$myClass = new MyClass;

foreach($myClass->generator() as $key => $value) {
  //Do something
}

```

<a name="data"></a>
## Data Trait

The data trait combines all the aforementioned traits so you wouldn't have to
declare them one by one *(on the off chance you would want to use all of them)*.
Mounting this trait can be done with the following example.

```

use UGComponents\Data\DataTrait;

class MyClass
{
  use DataTrait;
}

```

<a name="registry"></a>
## Registry Object

A registry object contains methods to easily manage a multidimensional array.
Implementing this object can be done like so.

```

use UGComponents\Data\Registry;

$registry1 = new Registry;

//or

$registry2 = new Registry(['zoo' => ['foo' => ['bar']]]);

```

On top of all the `DataTrait` methods, the registry implements 5 additional
methods.

`exists` - Returns true if the data has the given key path or whether
if there is data at all.

```

$registry1->exists(); //--> false
$registry2->exists(); //--> true

$registry2->exists('zoo'); //--> true
$registry2->exists('zoo', 'foo'); //--> true
$registry2->exists('foo', 'bar'); //--> false

```

----

`get` - Returns part of the data given the key path or the entire data set.

```

$registry2->get(); //--> ['zoo' => ['foo' => ['bar']]]

$registry2->get('zoo'); //--> ['foo' => ['bar']]
$registry2->get('zoo', 'foo'); //--> ['bar']
$registry2->get('zoo', 'foo', 0); //--> 'bar'

```

----

`isEmpty` - Returns true if the data, given key path is empty or whether
if there is data at all.

```

$registry1->isEmpty(); //--> true
$registry2->isEmpty(); //--> false

$registry2->isEmpty('zoo'); //--> false
$registry2->isEmpty('zoo', 'foo'); //--> false
$registry2->isEmpty('foo', 'bar'); //--> true

```

----

`remove` - Removes part of the data given the key path or the entire data set.

```

$registry2->remove('zoo', 'foo', 0); // 'bar'
$registry2->remove('zoo', 'foo'); // ['bar']
$registry2->remove('zoo'); // ['foo' => ['bar']]
$registry2->remove(); // ['zoo' => ['foo' => ['bar']]]

$registry2->remove('foo', 'bar'); // does nothing because it doesn't exist

```

----

`set` - Sets part of the data given the key path or the entire data set.

```

$registry2->set('zoo', 'foo', 0, 'bar'); // ['zoo' => ['foo' => ['bar']]]

```

<a name="model"></a>
## Model Object

A model object simply deals with key/value pairs. Models become useful when
pairing it with a data store, JSON results, services etc. Implementing this
object can be done like so.

```

use UGComponents\Data\Model;

$model1 = new Model;

//or

$model2 = new Model(['foo_bar' => 'zoo']);

```

On top of all the `DataTrait` methods, the model object implements 2 additional
methods.

`get` - Returns the entire data set.

```

$model2->get(); //--> ['foo_bar' => 'zoo']

```

----

`set` - Sets the entire data set.

```

$model1->set(['foo_bar' => 'zoo']);

```

<a name="collection"></a>
## Collection Object

A collection object helper manages a list of models. Collections become
useful when pairing it with a data store, JSON results, services etc.
Implementing this object can be done like so.

```

use UGComponents\Data\Collection;

$collection = new Collection;

```

A collection object only mounts `ArrayAccessTrait`, `CountableTrait`,
`GeneratorTrait`, `IteratorTrait` and implements 6 additional methods.

`add` - Appends a model to the collection

```

$collection->add(new Model);

```

----

`cut` - Removes a model in the collection given the index number and
reindexes the collection.

```

$collection->cut(1); //removes the second model
$collection->cut('first'); //removes the first model
$collection->cut('last'); //removes the last model

```

----

`each` - Loops through each model in the list

```

$collection->each(function($model) {
  //do something
});

```

----

`get` - Returns the entire dataset as pure arrays

```

$collection->each(function($model) {
  //do something
});

```

----

`getModel` - Transforms an array into a model

```

$collection->getModel(['foo' => 'bar']); //--> Model

```

----

`set` - Sets the collection

```

$collection->set([
  ['foo' => 'bar'],
  ['foo' => 'zoo']
])

```

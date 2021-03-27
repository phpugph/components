<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Data;

use ArrayAccess;
use Iterator;
use Countable;

use UGComponents\Data\Model\ModelInterface;
use UGComponents\Data\Model\ModelException;

use UGComponents\Event\EventTrait;

use UGComponents\Helper\InstanceTrait;
use UGComponents\Helper\LoopTrait;
use UGComponents\Helper\ConditionalTrait;

use UGComponents\Profiler\InspectorTrait;
use UGComponents\Profiler\LoggerTrait;

use UGComponents\Resolver\StateTrait;
use UGComponents\Resolver\ResolverException;

/**
 * Models are designed to easily manipulate $data in
 * preparation to integrate with any one dimensional
 * data store. This is the main model object.
 *
 * @package  PHPUGPH
 * @category Date
 * @standard PSR-2
 */
class Model implements ArrayAccess, Iterator, Countable, ModelInterface
{
  use DataTrait,
    EventTrait,
    InstanceTrait,
    LoopTrait,
    ConditionalTrait,
    InspectorTrait,
    LoggerTrait,
    StateTrait
    {
      DataTrait::__getData as __get;
      DataTrait::__setData as __set;
      DataTrait::__toStringData as __toString;
  }

  /**
   * Attempts to use __callData then __callResolver
   *
   * @param *string $name name of method
   * @param *array  $args arguments to pass
   *
   * @return mixed
   */
  public function __call(string $name, array $args)
  {
    try {
      return $this->__callData($name, $args);
    } catch (DataException $e) {
    }

    try {
      return $this->__callResolver($name, $args);
    } catch (ResolverException $e) {
      throw new ModelException($e->getMessage());
    }
  }

  /**
   * Presets the collection
   *
   * @param *array $data The initial data
   */
  public function __construct(array $data = [])
  {
    $this->set($data);
  }

  /**
   * Returns the entire data
   *
   * @return array
   */
  public function get(): array
  {
    return $this->data;
  }

  /**
   * Sets the entire data
   *
   * @param *array $data
   *
   * @return ModelInterface
   */
  public function set(array $data): ModelInterface
  {
    $this->data = $data;
    return $this;
  }
}

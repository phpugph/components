<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Package;

use Closure;

/**
 * Package space for package methods
 *
 * @vendor   UGComponents
 * @package  Package
 * @standard PSR-2
 */
class Package
{
  /**
   * @const string TYPE_PSEUDO
   */
  const TYPE_PSEUDO = 'pseudo';

  /**
   * @const string TYPE_ROOT
   */
  const TYPE_ROOT = 'root';

  /**
   * @const string TYPE_VENDOR
   */
  const TYPE_VENDOR = 'vendor';

  /**
   * @var ?PackageHandler $handler
   */
  protected $handler = null;

  /**
   * @var *string $name
   */
  protected $name;

  /**
   * @var array $methods A list of virtual methods
   */
  protected $methods = [];

  /**
   * @var ?string $path cache to remember the package path
   */
  protected $path = null;

  /**
   * Store the name so we can profile later
   *
   * @param *PackageHandler $handler
   * @param *string         $name
   * @param ?string         $root
   */
  public function __construct(PackageHandler $handler, $name, ?string $path = null)
  {
    $this->handler = $handler;
    $this->name = $name;
    $this->path = $path;
  }

  /**
   * When a method doesn't exist, it this will try to call one
   * of the virtual methods.
   *
   * @param *string $name name of method
   * @param *array  $args arguments to pass
   *
   * @return mixed
   */
  public function __call(string $name, array $args)
  {
    //use closure methods first
    if (isset($this->methods[$name])) {
      $results = call_user_func_array($this->methods[$name], $args);
      if (is_array($this->methods[$name])
        && isset($this->methods[$name][0])
        && $results instanceof $this->methods[$name][0]
      ) {
        return $this;
      }

      return $results;
    }

    throw PackageException::forMethodNotFound($this->name, $name);
  }

  /**
   * Registers a method to be used
   *
   * @param *string   $name     The class route name
   * @param *callable $callback The callback handler
   *
   * @return Package
   */
  public function addPackageMethod(string $name, callable $callback): Package
  {
    if ($callback instanceof Closure) {
      $callback = $callback->bindTo($this, get_class($this));
    }

    $this->methods[$name] = $callback;
    return $this;
  }

  /**
   * Returns the parent package handler
   *
   * @return ?PackageHandler
   */
  public function getPackageHandler(): ?PackageHandler
  {
    return $this->handler;
  }

  /**
   * Returns the package type
   *
   * @return ?object
   */
  public function getPackageMethods()
  {
    return $this->methods;
  }

  /**
   * Returns the path of the project
   *
   * @return string|false
   */
  public function getPackagePath()
  {
    if (!is_null($this->path)) {
      return $this->path;
    }

    switch ($this->getPackageType()) {
      case self::TYPE_ROOT:
        $this->path = $this->handler->getCwd() . $this->name;
        break;
      case self::TYPE_VENDOR:
        $this->path = sprintf(
          '%s/vendor/%s',
          $this->handler->getCwd(),
          $this->name
        );
        break;
      case self::TYPE_PSEUDO:
      default:
        $this->path = false;
        break;
    }

    return $this->path;
  }

  /**
   * Returns the package type
   *
   * @return string
   */
  public function getPackageType(): string
  {
    //if it starts with / like /foo/bar
    if (strpos($this->name, '/') === 0) {
      //it's a root package
      return self::TYPE_ROOT;
    }

    //if theres a slash like foo/bar
    if (strpos($this->name, '/') !== false) {
      //it's vendor package
      return self::TYPE_VENDOR;
    }

    //by default it's a pseudo package
    return self::TYPE_PSEUDO;
  }

  /**
   * Sets an object map for method calling
   *
   * @param *object $map
   * @param bool    $overwrite
   *
   * @return string
   */
  public function mapPackageMethods(object $map, $overwrite = false): Package
  {
    foreach (get_class_methods($map) as $method) {
      $this->addPackageMethod($method, [$map, $method]);
    }

    return $this;
  }
}

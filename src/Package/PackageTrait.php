<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Package;

/**
 * If you want to utilize composer packages
 * as plugins/extensions/addons you can adopt
 * this trait
 *
 * @vendor   UGComponents
 * @package  Package
 * @standard PSR-2
 */
trait PackageTrait
{
  /**
   * @var array $packages A safe place to store package junk
   */
  protected $packages = [];

  /**
   * Custom Invoker for package calling
   *
   * @param *string $package name of package
   *
   * @return Package
   */
  public function __invokePackage(string $package): Package
  {
    return $this->package($package);
  }

  /**
   * Returns all the packages
   *
   * @param string|null $vendor Name of package
   *
   * @return array
   */
  public function getPackages(string $vendor = null)
  {
    if (isset($this->packages[$vendor])) {
      return $this->packages[$vendor];
    }

    return $this->packages;
  }

  /**
   * Returns true if given is a registered package
   *
   * @param *string $vendor The vendor/package name
   *
   * @return bool
   */
  public function isPackage(string $vendor): bool
  {
    return isset($this->packages[$vendor]);
  }

  /**
   * Returns a package space
   *
   * @param *string $vendor The vendor/package name
   *
   * @return PackageTrait
   */
  public function package(string $vendor)
  {
    // @codeCoverageIgnoreStart
    //i tested and verified that coverage does go
    //through here, but it's not reported that it did...
    if (!isset($this->packages[$vendor])) {
      throw PackageException::forPackageNotFound($vendor);
    }
    // @codeCoverageIgnoreEnd

    return $this->packages[$vendor];
  }

  /**
   * Registers and initializes a plugin
   *
   * @param *string $vendor   The vendor/package name
   * @param ?string $root     The path location of the package
   * @param string $bootstrap A file to call on when a package is registered
   *
   * @return PackageTrait
   */
  public function register(
    string $vendor,
    ?string $root = null,
    ?string $bootstrap = null
  ) {
    //if no bootstrap file name defined and theres a getBootstrapFileName method
    if (is_null($bootstrap) && method_exists($this, 'getBootstrapFileName')) {
      //use that
      $bootstrap = $this->getBootstrapFileName();
    }

    // @codeCoverageIgnoreStart
    //if still no bootstrap file name defined
    if (is_null($bootstrap)) {
      //set it to something at least...
      $bootstrap = 'bootstrap';
    }
    // @codeCoverageIgnoreEnd

    //determine class
    if (method_exists($this, 'resolve')) {
      $this->packages[$vendor] = $this->resolve(Package::class, $this, $vendor, $root);
    // @codeCoverageIgnoreStart
    } else {
      $this->packages[$vendor] = new Package($this, $vendor, $root);
    }
    // @codeCoverageIgnoreEnd

    $path = $this->packages[$vendor]->getPackagePath();

    if (!$path) {
      return $this;
    }

    $file = sprintf('%s/%s', $path, $bootstrap);

    // @codeCoverageIgnoreStart
    if (file_exists($file)) {
      //so you can access the scope within the included file
      include $file;
    } else if (file_exists($file . '.php')) {
      include $file . '.php';
    }
    // @codeCoverageIgnoreEnd

    return $this;
  }
}

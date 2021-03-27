<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Package;

/**
 * Handler for micro framework calls. Combines both
 * Http handling and Event handling
 *
 * @vendor   UGComponents
 * @package  Package
 * @standard PSR-2
 */
class PackageHandler
{
  use PackageTrait
    {
      PackageTrait::__invokePackage as __invoke;
  }

  /**
   * @const string BOOTSTRAP_FILE the default bootstrap file name
   */
  const BOOTSTRAP_FILE = 'bootstrap';

  /**
   * @var *string $cwd the current working directory
   */
  protected $cwd;

  /**
   * Setup the default cwd
   */
  public function __construct()
  {
    //Note: dont trust getcwd because of symlinks..
    $this->cwd = getcwd();
  }

  /**
   * Returns the current working directory
   *
   * @return string
   */
  public function getCwd(): string
  {
    return $this->cwd;
  }

  /**
   * Returns the default bootstrap file name
   *
   * @return string
   */
  public function getBootstrapFileName(): string
  {
    return static::BOOTSTRAP_FILE;
  }

  /**
   * Sets the current working directory
   *
   * @param *string $cwd
   *
   * @return PackageHandler
   */
  public function setCwd(string $cwd): PackageHandler
  {
    if (!is_dir($cwd)) {
      throw PackageException::forFolderNotFound($cwd);
    }

    $this->cwd = $cwd;
    return $this;
  }
}

<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Package;

use Exception;

/**
 * Package exceptions
 *
 * @package  PHPUGPH
 * @category Package
 * @standard PSR-2
 */
class PackageException extends Exception
{
  /**
   * @const ERROR_FILE_NOT_FOUND Error template
   */
  const ERROR_FILE_NOT_FOUND = 'File %s was not found';

  /**
   * @const ERROR_FOLDER_NOT_FOUND Error template
   */
  const ERROR_FOLDER_NOT_FOUND = 'Folder %s was not found';

  /**
   * @const ERROR_METHOD_NOT_FOUND Error template
   */
  const ERROR_METHOD_NOT_FOUND = 'No method named %s was found in package %s';

  /**
   * @const string ERROR_PACKAGE_NOT_FOUND Error template
   */
  const ERROR_PACKAGE_NOT_FOUND = 'Could not find package: %s';

  /**
   * Create a new exception for file not found
   *
   * @param *string $path
   *
   * @return PackageException
   */
  public static function forFileNotFound(string $path): PackageException
  {
    return new static(sprintf(static::ERROR_FILE_NOT_FOUND, $path));
  }

  /**
   * Create a new exception for folder not found
   *
   * @param *string $path
   *
   * @return PackageException
   */
  public static function forFolderNotFound(string $path): PackageException
  {
    return new static(sprintf(static::ERROR_FOLDER_NOT_FOUND, $path));
  }

  /**
   * Create a new exception for invalid method
   *
   * @param *string $vendor
   * @param *string $name
   *
   * @return PackageException
   */
  public static function forMethodNotFound(
    string $vendor,
    string $name
  ): PackageException {
    return new static(sprintf(static::ERROR_METHOD_NOT_FOUND, $name, $vendor));
  }

  /**
   * Create a new exception for invalid package
   *
   * @param *string $vendor
   *
   * @return PackageException
   */
  public static function forPackageNotFound(string $vendor): PackageException
  {
    return new static(sprintf(static::ERROR_PACKAGE_NOT_FOUND, $vendor));
  }
}

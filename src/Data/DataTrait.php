<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Data;

/**
 * The DataTrait combines all the data features
 * in the Data package. Just a shortcut for having
 * all the features in one go.
 *
 * @package  PHPUGPH
 * @category Data
 * @standard PSR-2
 */
trait DataTrait
{
  use ArrayAccessTrait,
    IteratorTrait,
    CountableTrait,
    DotTrait,
    MagicTrait,
    GeneratorTrait,
    CloneTrait;

  /**
   * @var array $ata registered data
   */
  protected $data = [];

  /**
   * Attempts to copy from one key/value to key
   *
   * @param *string $source
   * @param *string $destination
   * @param string  $separator
   *
   * @return DataTrait
   */
  public function copy(
    string $source,
    string $destination,
    string $separator = '.'
  ) {
    //if there is a source key in the data
    if ($this->isDot($source, $separator)) {
      //send it over to the destiination
      $value = $this->getDot($source, $separator);
      $this->setDot($destination, $value, $separator);
    //if the destination exists
    } else if ($this->isDot($destination, $separator)) {
      //the source doesnt exist, so set it to null
      $this->setDot($destination, null, $separator);
    }

    //if the source and destination does not exist, do nothing
    return $this;
  }

  /**
   * Truncates data
   *
   * @return DataTrait
   */
  public function purge()
  {
    $this->data = [];
    return $this;
  }
}

<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\I18n;

use ArrayAccess;
use Iterator;

use UGComponents\Data\ArrayAccessTrait;
use UGComponents\Data\IteratorTrait;
use UGComponents\Data\MagicTrait;
use UGComponents\Data\GeneratorTrait;
use UGComponents\Data\DataException;

use UGComponents\Event\EventTrait;

use UGComponents\Helper\InstanceTrait;
use UGComponents\Helper\LoopTrait;
use UGComponents\Helper\ConditionalTrait;

use UGComponents\Profiler\InspectorTrait;
use UGComponents\Profiler\LoggerTrait;

use UGComponents\Resolver\StateTrait;
use UGComponents\Resolver\ResolverException;

/**
 * Language class implementation
 *
 * @vendor   UGComponents
 * @package  Language
 * @standard PSR-2
 */
class Language implements ArrayAccess, Iterator
{
  use ArrayAccessTrait,
    IteratorTrait,
    MagicTrait,
    GeneratorTrait,
    EventTrait,
    InstanceTrait,
    LoopTrait,
    ConditionalTrait,
    InspectorTrait,
    LoggerTrait,
    StateTrait
    {
      MagicTrait::__getData as __get;
      MagicTrait::__setData as __set;
      MagicTrait::__toStringData as __toString;
  }

  /**
   * @var string $file The language file to save to
   */
  protected $file = null;

  /**
   * @var array $data The translation list
   */
  protected $data = [];

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
      throw new LanguageException($e->getMessage());
    }
  }

  /**
   * Loads the translation set
   *
   * @param string|array $language the translation to load
   */
  public function __construct($language = [])
  {
    $this->setLanguage($language);
  }

  /**
   * Sets the translated value to the specified key
   *
   * @param *string $key   The translation key
   * @param *string $value The default value if we cannot find the translation
   *
   * @return Language
   */
  public function define($key, $value): Language
  {
    $this->data[$key] = $value;
    return $this;
  }

  /**
   * Returns the translated key.
   * if the key is not set it will set
   * the key to the value of the key
   *
   * @param *string $key
   * @param array   $args   The path if you want to set it
   *
   * @return string
   */
  public function translate(string $key, ...$args): string
  {
    if (!isset($this->data[$key])) {
      $this->data[$key] = $key;
    }

    //if we have arguments
    if (count($args)) {
      //sprintf it
      return vsprintf($this->data[$key], $args);
    }

    return $this->data[$key];
  }

  /**
   * Return the language set
   *
   * @return array
   */
  public function getLanguage(): array
  {
    return $this->data;
  }

  /**
   * Saves the language to a file
   *
   * @param string|null $file The file to save to
   *
   * @return Language
   */
  public function save($file = null): Language
  {
    if (is_null($file) && is_null($this->file)) {
      throw LanguageException::forFileNotSet();
    }

    if (is_null($file)) {
      $file = $this->file;
    }

    $contents = "<?php //-->\nreturn " . var_export($this->data, true) . ";";
    file_put_contents($file, $contents);

    return $this;
  }

  /**
   * Sets the entire data
   *
   * @param string|array $language the translation to load
   *
   * @return Language
   */
  public function setLanguage($language = []): Language
  {
    if (is_string($language)) {
      $this->file = $language;
      $language = include($language);
    }

    $this->data = $language;
    return $this;
  }
}

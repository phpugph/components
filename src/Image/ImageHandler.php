<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Image;

use UGComponents\Helper\InstanceTrait;

/**
 * Main image manipulation class
 *
 * @vendor   UGComponents
 * @package  Image
 * @standard PSR-2
 */
class ImageHandler
{
  use InstanceTrait;

  /**
   * @var [RESOURCE] $resource The GD Resource
   */
  protected $resource = null;

  /**
   * @var int $width The width of the image for meta
   */
  protected $width = 0;

  /**
   * @var int $height The height of the image for meta
   */
  protected $height = 0;

  /**
   * Pre set the image data
   *
   * @param *string   The raw data
   * @param string|null The mime type
   * @param bool    If the raw data is a file path
   * @param int     The quality of the image to output
   */
  public function __construct($data, $type = null, $path = true, $quality = 75)
  {
    $this->type = $type;

    //some render functions allow you
    //to set the quality of the render
    $this->quality = $quality;

    //create the resource
    $this->resource = $this->createResource($data, $path);

    //set the initial with and height
    list($this->width, $this->height) = $this->getDimensions();
  }

  /**
   * Incase we forget lets destroy the image
   */
  public function __destruct()
  {
    if ($this->resource) {
      imagedestroy($this->resource);
      $this->resource = null;
    }
  }

  /**
   * Renders the image to raw data
   *
   * @return string
   */
  public function __toString()
  {
    #imagepng() - Output a PNG image to either the browser or a file
    #imagegif() - Output image to browser or file
    #imagewbmp() - Output image to browser or file
    #imagejpeg() - Output image to browser or file
    ob_start();
    switch ($this->type) {
      case 'gif':
        imagegif($this->resource);
        break;
      case 'png':
        $quality = (100 - $this->quality) / 10;

        if ($quality > 9) {
          $quality = 9;
        }

        imagepng($this->resource, null, floor($quality));
        break;
      case 'bmp':
      case 'wbmp':
        imagewbmp($this->resource, null, floor($this->quality));
        break;
      case 'jpg':
      case 'jpeg':
      case 'pjpeg':
      default:
        imagejpeg($this->resource, null, floor($this->quality));
        break;
    }

    return ob_get_clean();
  }

  /**
   * Applies the selective blur filter. Blurs the image
   *
   * @return ImageHandler
   */
  public function blur()
  {
    //apply filter
    imagefilter($this->resource, IMG_FILTER_SELECTIVE_BLUR);

    return $this;
  }

  /**
   * Applies the brightness filter. Changes the brightness of the image.
   *
   * @param *number $level The level of brightness
   *
   * @return ImageHandler
   */
  public function brightness($level)
  {
    //apply filter
    imagefilter($this->resource, IMG_FILTER_BRIGHTNESS, $level);

    return $this;
  }

  /**
   * Applies the colorize filter. Like greyscale except you can specify the color.
   *
   * @param *number $red   The 255 value of red to use
   * @param *number $blue  The 255 value of blue to use
   * @param *number $green The 255 value of green to use
   * @param number  $alpha The level of alpha transparency
   *
   * @return ImageHandler
   */
  public function colorize($red, $blue, $green, $alpha = 0)
  {
    //apply filter
    imagefilter($this->resource, IMG_FILTER_COLORIZE, $red, $blue, $green, $alpha);

    return $this;
  }

  /**
   * Applies the contrast filter. Changes the contrast of the image.
   *
   * @param *number $level The level of contrast
   *
   * @return ImageHandler
   */
  public function contrast($level)
  {
    //apply filter
    imagefilter($this->resource, IMG_FILTER_CONTRAST, $level);

    return $this;
  }

  /**
   * Crops the image
   *
   * @param int|null $width  The width; If null will use the original width
   * @param int|null $height The height; If null will use the original height
   *
   * @return ImageHandler
   */
  public function crop($width = null, $height = null)
  {
    //get the source width and height
    $orgWidth = imagesx($this->resource);
    $orgHeight = imagesy($this->resource);

    //set the width if none is defined
    if (is_null($width)) {
      $width = $orgWidth;
    }

    //set the height if none is defined
    if (is_null($height)) {
      $height = $orgHeight;
    }

    //if the width and height are the same as the originals
    if ($width == $orgWidth && $height == $orgHeight) {
      //there's no need to process
      return $this;
    }

    //if we are here then we do need to crop
    //create the new resource with the width and height
    $crop = imagecreatetruecolor($width, $height);

    //set some defaults
    $xPosition = 0;
    $yPosition = 0;

    //if the width is greater than the original width
    //or if the height is greater than the original height
    if ($width > $orgWidth || $height > $orgHeight) {
      //save the destination width and height
      //because they will change here
      $newWidth = $width;
      $newHeight = $height;

      //if the desired height is larger than the desired width
      if ($height > $width) {
        //and adjust the height instead
        $height = $this->getHeightAspectRatio($orgWidth, $orgHeight, $width);
        //if the aspect height is bigger than the desired height
        if ($newHeight > $height) {
          //set it back to the desired height
          $height = $newHeight;
          //and adjust the width instead
          $width = $this->getWidthAspectRatio($orgWidth, $orgHeight, $height);
          //now because of the way GD renders we need to find the ratio of desired
          //height if it was brought down to the original height
          $rWidth = $this->getWidthAspectRatio($newWidth, $newHeight, $orgHeight);
          //set the x Position of the source to the center of the
          //original width image width minus half the rWidth width
          $xPosition = ($orgWidth / 2) - ($rWidth / 2);
        } else {
          //now because of the way GD renders we need to find the ratio of desired
          //height if it was brought down to the original height
          $rHeight = $this->getHeightAspectRatio($newWidth, $newHeight, $orgWidth);
          //set the y Position of the source to the center of the
          //new sized image height minus half the desired height
          $yPosition = ($orgHeight / 2) - ($rHeight / 2) ;
        }
      //if the desired height is smaller than the desired width
      } else {
        //get the width aspect ratio
        $width = $this->getWidthAspectRatio($orgWidth, $orgHeight, $height);
        //if the aspect height is bigger than the desired height
        if ($newWidth > $width) {
          //set it back to the desired height
          $width = $newWidth;
          //and adjust the width instead
          $height = $this->getHeightAspectRatio($orgWidth, $orgHeight, $width);
          //now because of the way GD renders we need to find the ratio of desired
          //height if it was brought down to the original height
          $rHeight = $this->getHeightAspectRatio($newWidth, $newHeight, $orgWidth);
          //set the y Position of the source to the center of the
          //new sized image height minus half the desired height
          $yPosition = ($orgHeight / 2) - ($rHeight / 2) ;
        } else {
          //now because of the way GD renders we need to find the ratio of desired
          //height if it was brought down to the original height
          $rWidth = $this->getWidthAspectRatio($newWidth, $newHeight, $orgHeight);
          //set the x Position of the source to the center of the
          //original width image width minus half the rWidth width
          $xPosition = ($orgWidth / 2) - ($rWidth / 2);
        }
      }
    } else {
      //if the width is less than the original width
      if ($width < $orgWidth) {
        //set the x Position of the source to the center of the
        //original image width minus half the desired width
        $xPosition = ($orgWidth / 2) - ($width / 2);
        //set the destination width to be the original width
        $width = $orgWidth;
      }

      //if the height is less than the original height
      if ($height < $orgHeight) {
        //set the y Position of the source to the center of the
        //original image height minus half the desired height
        $yPosition = ($orgHeight / 2) - ($height / 2);
        //set the destination height to be the original height
        $height = $orgHeight;
      }
    }

    //render the image
    imagecopyresampled(
      $crop, 
      $this->resource, 
      0, 0, 
      floor($xPosition), 
      floor($yPosition), 
      floor($width), 
      floor($height), 
      floor($orgWidth), 
      floor($orgHeight)
    );

    //destroy the original resource
    imagedestroy($this->resource);

    //assign the new resource
    $this->resource = $crop;

    return $this;
  }

  /**
   * Applies the edgedetect filter. Uses edge detection to highlight the edges in the image.
   *
   * @return ImageHandler
   */
  public function edgedetect()
  {
    //apply filter
    imagefilter($this->resource, IMG_FILTER_EDGEDETECT);

    return $this;
  }

  /**
   * Applies the emboss filter. Embosses the image.
   *
   * @return ImageHandler
   */
  public function emboss()
  {
    //apply filter
    imagefilter($this->resource, IMG_FILTER_EMBOSS);

    return $this;
  }

  /**
   * Applies the gaussian blur filter. Blurs the image using the Gaussian method.
   *
   * @return ImageHandler
   */
  public function gaussianBlur()
  {
    //apply filter
    imagefilter($this->resource, IMG_FILTER_GAUSSIAN_BLUR);

    return $this;
  }

  /**
   * Returns the size of the image
   *
   * @return array
   */
  public function getDimensions()
  {
    return [imagesx($this->resource), imagesy($this->resource)];
  }

  /**
   * Returns the resource for custom editing
   *
   * @return [RESOURCE]
   */
  public function getResource()
  {
    return $this->resource;
  }

  /**
   * Applies the greyscale filter. Converts the image into grayscale.
   *
   * @return ImageHandler
   */
  public function greyscale()
  {
    //apply filter
    imagefilter($this->resource, IMG_FILTER_GRAYSCALE);

    return $this;
  }

  /**
   * Inverts the image.
   *
   * @param bool $vertical If true invert vertical; if false invert horizontal
   *
   * @return ImageHandler
   */
  public function invert($vertical = false)
  {
    //get the source width and height
    $orgWidth = imagesx($this->resource);
    $orgHeight = imagesy($this->resource);

    $invert = imagecreatetruecolor($orgWidth, $orgHeight);

    if ($vertical) {
      imagecopyresampled(
        $invert,
        $this->resource,
        0,
        0,
        0,
        $orgHeight-1,
        $orgWidth,
        $orgHeight,
        $orgWidth,
        0-$orgHeight
      );
    } else {
      imagecopyresampled(
        $invert,
        $this->resource,
        0,
        0,
        $orgWidth-1,
        0,
        $orgWidth,
        $orgHeight,
        0-$orgWidth,
        $orgHeight
      );
    }

    //destroy the original resource
    imagedestroy($this->resource);

    //assign the new resource
    $this->resource = $invert;

    return $this;
  }

  /**
   * Applies the mean removal filter. Uses mean removal to achieve a "sketchy" effect.
   *
   * @return ImageHandler
   */
  public function meanRemoval()
  {
    //apply filter
    imagefilter($this->resource, IMG_FILTER_MEAN_REMOVAL);

    return $this;
  }

  /**
   * Applies the greyscale filter. Reverses all colors of the image.
   *
   * @return ImageHandler
   */
  public function negative()
  {
    //apply filter
    imagefilter($this->resource, IMG_FILTER_NEGATE);

    return $this;
  }

  /**
   * Resizes the image. This is a version of
   * scale but keeping it's original aspect ratio
   *
   * @param int|null $width  the width; if null will use the original width
   * @param int|null $height the height; if null will use the original height
   *
   * @return ImageHandler
   */
  public function resize($width = null, $height = null)
  {
    //get the source width and height
    $orgWidth = imagesx($this->resource);
    $orgHeight = imagesy($this->resource);

    //set the width if none is defined
    if (is_null($width)) {
      $width = $orgWidth;
    }

    //set the height if none is defined
    if (is_null($height)) {
      $height = $orgHeight;
    }

    //if the width and height are the same as the originals
    if ($width == $orgWidth && $height == $orgHeight) {
      //there's no need to process
      return $this;
    }

    $newWidth = $width;
    $newHeight = $height;

    //if the desired height is larger than the desired width
    if ($height < $width) {
      //get the width aspect ratio
      $width = $this->getWidthAspectRatio($orgWidth, $orgHeight, $height);
      //if the aspect width is bigger than the desired width
      if ($newWidth < $width) {
        //set it back to the desired width
        $width = $newWidth;
        //and adjust the height instead
        $height = $this->getHeightAspectRatio($orgWidth, $orgHeight, $width);
      }
    //if the desired height is smaller than the desired width
    } else {
      //get the width aspect ratio
      $height = $this->getHeightAspectRatio($orgWidth, $orgHeight, $width);
      //if the aspect height is bigger than the desired height
      if ($newHeight < $height) {
        //set it back to the desired height
        $height = $newHeight;
        //and adjust the width instead
        $width = $this->getWidthAspectRatio($orgWidth, $orgHeight, $height);
      }
    }

    return $this->scale($width, $height);
  }

  /**
   * Rotates the image.
   *
   * @param *int $degree   The degree to rotate by
   * @param int  $background Background color code
   *
   * @return ImageHandler
   */
  public function rotate($degree, $background = 0)
  {
    //rotate the image
    $rotate = imagerotate($this->resource, $degree, $background);

    //destroy the original resource
    imagedestroy($this->resource);

    //assign the new resource
    $this->resource = $rotate;

    return $this;
  }

  /**
   * Scales the image. If width or height is set
   * to null a width or height will be auto determined based on the
   * aspect ratio
   *
   * @param int|null $width  The width; if null will use the original width
   * @param int|null $height The height; if null will use the original height
   *
   * @return ImageHandler
   */
  public function scale($width = null, $height = null)
  {
    //get the source width and height
    $orgWidth = imagesx($this->resource);
    $orgHeight = imagesy($this->resource);

    //set the width if none is defined
    if (is_null($width)) {
      $width = $orgWidth;
    }

    //set the height if none is defined
    if (is_null($height)) {
      $height = $orgHeight;
    }

    //if the width and height are the same as the originals
    if ($width == $orgWidth && $height == $orgHeight) {
      //there's no need to process
      return $this;
    }

    //if we are here then we do need to crop
    //create the new resource with the width and height
    $scale = imagecreatetruecolor($width, $height);

    //render the image
    imagecopyresampled($scale, $this->resource, 0, 0, 0, 0, $width, $height, $orgWidth, $orgHeight);

    //destroy the original resource
    imagedestroy($this->resource);

    //assign the new resource
    $this->resource = $scale;

    return $this;
  }

  /**
   * Sets the background color to be transparent
   *
   * @return ImageHandler
   */
  public function setTransparency()
  {
    imagealphablending($this->resource, false);
    imagesavealpha($this->resource, true);

    return $this;
  }

  /**
   * Applies the smooth filter. Makes the image smoother.
   *
   * @param *number $level The level of smoothness
   *
   * @return ImageHandler
   */
  public function smooth($level)
  {
    //apply filter
    imagefilter($this->resource, IMG_FILTER_SMOOTH, $level);

    return $this;
  }

  /**
   * Saves the image data to a file
   *
   * @param *string   $path The path to save to
   * @param string|null $type The render type
   *
   * @return ImageHandler
   */
  public function save($path, $type = null)
  {
    #imagepng() - Output a PNG image to either the browser or a file
    #imagegif() - Output image to browser or file
    #imagewbmp() - Output image to browser or file
    #imagejpeg() - Output image to browser or file

    if (!$type) {
      $type = $this->type;
    }

    switch ($type) {
      case 'gif':
        imagegif($this->resource, $path);
        break;
      case 'png':
        $quality = (100 - $this->quality) / 10;

        if ($quality > 9) {
          $quality = 9;
        }

        imagepng($this->resource, $path, floor($quality));
        break;
      case 'bmp':
      case 'wbmp':
        imagewbmp($this->resource, $path, floor($this->quality));
        break;
      case 'jpg':
      // @codeCoverageIgnoreStart
      case 'jpeg':
      case 'pjpeg':
      // @codeCoverageIgnoreEnd
      default:
        imagejpeg($this->resource, $path, floor($this->quality));
        break;
    }

    return $this;
  }

  /**
   * Returns the GD image resource
   *
   * @param *string $data The raw image data
   * @param *string $path Whether if this raw data is a file path
   *
   * @return [RESOURCE]
   */
  protected function createResource($data, $path)
  {
    // @codeCoverageIgnoreStart
    //if the GD Library is not installed
    if (!function_exists('gd_info')) {
      //throw error
      throw ImageException::forGDNotInstalled();
    }
    // @codeCoverageIgnoreEnd

    # imagecreatefromgd — Create a new image from GD file or URL
    # imagecreatefromgif — Create a new image from file or URL
    # imagecreatefromjpeg — Create a new image from file or URL
    # imagecreatefrompng — Create a new image from file or URL
    # imagecreatefromstring — Create a new image from the image stream in the string
    # imagecreatefromwbmp — Create a new image from file or URL
    # imagecreatefromxbm — Create a new image from file or URL
    # imagecreatefromxpm — Create a new image from file or URL

    $resource = false;

    if (!$path) {
      return imagecreatefromstring($data);
    }

    //depending on the extension lets load
    //the file using the right GD loader
    switch ($this->type) {
      // @codeCoverageIgnoreStart
      case 'gd':
        $resource = imagecreatefromgd($data);
        break;
      // @codeCoverageIgnoreEnd
      case 'gif':
        $resource = imagecreatefromgif($data);
        break;
      case 'jpg':
      case 'jpeg':
      case 'pjpeg':
        $resource = imagecreatefromjpeg($data);
        break;
      case 'png':
        $resource = imagecreatefrompng($data);
        break;
      case 'bmp':
      case 'wbmp':
        $resource = imagecreatefromwbmp($data);
        break;
      case 'xbm':
        $resource = imagecreatefromxbm($data);
        break;
      case 'xpm':
        $resource = imagecreatefromxpm($data);
        break;
    }

    //if there is no resource still
    if (!$resource) {
      //throw error
      ImageException::forInvalidImageFile($path);
    }

    return $resource;
  }

  /**
   * Determines the preserved height given the original dimensions and the width
   *
   * @param *number $sourceWidth    The original width
   * @param *number $sourceHeight   The original width
   * @param *number $destinationWidth The desired width
   *
   * @return number
   */
  protected function getHeightAspectRatio($sourceWidth, $sourceHeight, $destinationWidth)
  {
    $ratio = $destinationWidth / $sourceWidth;
    return  $sourceHeight * $ratio;
  }

  /**
   * Determines the preserved width given the original dimensions and the height
   *
   * @param *number $sourceWidth     The original width
   * @param *number $sourceHeight    The original width
   * @param *number $destinationHeight The desired Height
   *
   * @return number
   */
  protected function getWidthAspectRatio($sourceWidth, $sourceHeight, $destinationHeight)
  {
    $ratio = $destinationHeight / $sourceHeight;
    return  $sourceWidth * $ratio;
  }
}

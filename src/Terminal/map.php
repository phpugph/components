<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

//echos aren't testable
return function ($message, $color = null) {
  if (is_string($color)) {
    $message = sprintf($color, $message);
  }

  print $message;
};

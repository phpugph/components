<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

return function ($handler) {
  register_shutdown_function(function () use ($handler) {
    $error = error_get_last();
    if (!$error || ($error['type'] ^ E_ERROR)) {
      $handler->run();
    }
  });
};

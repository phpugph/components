<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

return function (array $options = array()) {
  $curl = curl_init();
  curl_setopt_array($curl, $options);
  $response = curl_exec($curl);

  $meta = [
    'info' => curl_getinfo($curl, CURLINFO_HTTP_CODE),
    'error_message' => curl_errno($curl),
    'error_code' => curl_error($curl),
    'response' => $response
  ];

  curl_close($curl);
  unset($curl);

  return $meta;
};

<?php

use \Tsugi\Util\U;
use \Tsugi\Core\LTIX;

require_once "../../config.php";

$pieces = U::rest_path();
if ( ! isset($pieces->controller) || strlen($pieces->controller) < 1 ) {
    http_response_code(500);
    die("missing session");
}

// Force the session ID REST style :)
$_GET[session_name()] = $pieces->controller;
$LAUNCH = LTIX::requireData();

echo("<pre>\n");
var_dump($LAUNCH);


/*
 object(stdClass)#7 (8) {
  ["parent"]=>
  string(23) "/py4e/mod/ckpaper/store"
  ["base_url"]=>
  string(21) "http://localhost:8888"
  ["controller"]=>
  string(17) "92992929292929292"
  ["extra"]=>
  string(3) "zap"
  ["action"]=>
  string(3) "zap"
  ["parameters"]=>
  array(0) {
  }
  ["current"]=>
  string(41) "/py4e/mod/ckpaper/store/92992929292929292"
  ["full"]=>
  string(45) "/py4e/mod/ckpaper/store/92992929292929292/zap"
}
 */

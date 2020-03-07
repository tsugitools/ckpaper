<?php

use \Tsugi\Util\U;
use \Tsugi\Core\LTIX;

require_once "../../config.php";

$pieces = U::rest_path();
if ( ! isset($pieces->controller) || strlen($pieces->controller) < 1 ) {
    http_response_code(500);
    echo("<pre>\nMissing Session\n\n");
    echo(htmlentities(print_r($pieces, TRUE)));
    die();
}

// Force the session ID REST style :)
$_GET[session_name()] = $pieces->controller;
$LAUNCH = LTIX::requireData();

// http://docs.annotatorjs.org/en/v1.2.x/storage.html#core-storage-api
if ( strlen($pieces->action) < 1 ) {
    $retval = array(
          "name" => "Annotator Store API",
          "version" => "2.0.0",
          "author" => "Charles R. Severance"
    );
    header('Content-Type: application/json; charset=utf-8');
    echo(json_encode($retval, JSON_PRETTY_PRINT));
    return;
}

if ( ! trim($pieces->action) == 'annotations' ) {
    http_response_code(404);
    die("Expecting 'session-id/annotations'");
}
    

if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
    $annotations = $LAUNCH->result->getJsonKey('annotations', '[ ]');
    $retval = json_decode($annotations);
    if ( ! is_array($retval) ) $retval = array();
    header('Content-Type: application/json; charset=utf-8');
    echo(json_encode($retval, JSON_PRETTY_PRINT));
    return;
}

http_response_code(405);
die("Working on the rest...");



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

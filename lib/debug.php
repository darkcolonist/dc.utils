<?php
static function debug($variable) {
  return '<pre>' . print_r($variable, true) . '</pre>';
}

/**
 * display backtrace of the code, and the list of files the code has
 * passed through to reach this function.
 *
 * example:
 *   Array
 *   (
 *       [0] => /www/index.php:118
 *       [1] => /www/system/core/Bootstrap.php:55
 *       [2] => /www/system/core/Event.php:209
 *       [3] => /www/system/core/Kohana.php:291
 *       [4] => /www/application/controllers/test/util.php:92
 *   )
 * 
 * @param  string $type [string|array]
 * @return mixed       [depends on $type]
 */
static function backtrace($type = "array"){
  $removeFromFilename = array(
    '/www/',
    '.php'
  );

  $removeFromTrace = array(
    "system/core/Kohana.php",
    "system/core/Event.php",
    "system/core/Bootstrap.php",
    "index.php"
  );

  $backtrace = debug_backtrace();

  $simplifiedBacktrace = array();

  foreach ($backtrace as $btkey => $backitem) {
    if(isset($backitem['file'])) {
      foreach ($removeFromTrace as $rftkey => $rftval) {
        if(strpos($backitem['file'], $rftval) !== false)
          continue 2;
      }

      foreach ($removeFromFilename as $rffkey => $rffval) {
        $backitem['file'] = str_replace($rffval, '', $backitem['file']);
      }

      $simplifiedBacktrace[] = $backitem['file'].":".$backitem['line'];
    }
  }

  $simplifiedBacktrace = array_reverse($simplifiedBacktrace);

  if($type == 'array'){
    return $simplifiedBacktrace;
  }else{
    $stringify = '';

    $simplifiedBacktrace = array_reverse($simplifiedBacktrace);

    foreach ($simplifiedBacktrace as $sbtkey => $sbtval) {
      $stringify .= "$sbtval < ";
    }

    $stringify = rtrim($stringify, " < ");

    // stringify the array
    return $stringify;
  }
}

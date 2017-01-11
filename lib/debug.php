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
 * @param  int $directorySubstr [0 for whole name, n for first n characters of name]
 * @return mixed       [depends on $type]
 */
static function backtrace($type = "array", $directorySubstr = 0){
  $trimFromFilePath = array(
    '/www/',
    '.php'
  );
  $omitFromTrace = array(
    "system/core/Kohana.php",
    "system/core/Event.php",
    "system/core/Bootstrap.php",
    "index.php"
  );
  $backtrace = debug_backtrace();
  $simplifiedBacktrace = array();
  foreach ($backtrace as $btkey => $backitem) {
    if(isset($backitem['file'])) {
      foreach ($omitFromTrace as $rftkey => $rftval) {
        if(strpos($backitem['file'], $rftval) !== false)
          continue 2;
      }
      foreach ($trimFromFilePath as $rffkey => $rffval) {
        $backitem['file'] = str_replace($rffval, '', $backitem['file']);
      }
      if($directorySubstr != 0){
        $fileSubsecs = explode(DIRECTORY_SEPARATOR, $backitem['file']);
        foreach ($fileSubsecs as $fsskey => $fssval) {
          if($fsskey != count($fileSubsecs) - 1){
            $fileSubsecs[$fsskey] = substr($fileSubsecs[$fsskey], 0, $directorySubstr);
          }
        }
        $backitem['file'] = implode(DIRECTORY_SEPARATOR, $fileSubsecs);
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

private static $_trace2file = array();
/**
 * dependencies:
 *   log.php::log()
 *   date.php::microtime()
 *   debug.php::backtrace()
 *
 * saves a function trace
 *
 * used when tracking down bottlenecks and application slowdowns
 * put a util::trace2file in several locations to make debugging
 * thorough
 * 
 * @param  string $message the string to be stored in text-file
 * @param  string $file    the file to store the trace
 * @param  string $folder  the folder where the file will be saved
 * @return bool            
 */
static function trace2file($message = null, $file = 'trace2file', $folder = 'debug'){
  if(count(self::$_trace2file) == 0){
    $hash = substr(uniqid(), -4, 4);

    self::$_trace2file['hash'] = $hash;
    self::$_trace2file['start'] = self::microtime();
    self::$_trace2file['count'] = 0;
    self::$_trace2file['totaltime'] = 0;
  }

  self::$_trace2file['count'] ++;        

  $timestamp = self::microtime();
  $timestamp = substr($timestamp, 8, 6);
  $timestamp = number_format($timestamp, 3);
  $elapsed = 0;

  $elapsed = $timestamp - self::$_trace2file['start'];

  self::$_trace2file['start'] = $timestamp;

  if($elapsed < 0)
    $elapsed = 0;

  $elapsed = number_format($elapsed, 3);

  self::$_trace2file['totaltime'] += $elapsed;

  $totaldisp = number_format(self::$_trace2file['totaltime'], 1);

  if(trim($message) == ""){
    $backtrace = self::backtrace('array');
    $message = $backtrace[count($backtrace)-2];
  }

  
  $message = self::$_trace2file['hash'].'|'.self::$_trace2file['count'].'|'.$elapsed.'/'.$totaldisp.' '.$message;

  self::log($message, $file, $folder);

  return true;
}

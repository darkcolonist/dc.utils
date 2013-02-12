<?php
/**
 * depends on: date.php
 * depends on: debug.php
 */
protected static $_trace_enabled = false;
static $stacktrace = array();

static function enable_tracing() {
  self::$_trace_enabled = true;
}

static function disable_tracing() {
  self::$_trace_enabled = false;
  self::$stacktrace = array();
}

/**
 *
 * profiler type tracing
 * trace variables, objects as well as message strings
 * also shows queue #, time, memory usage (MB)
 *
 * @param mixed $message
 */
static function add_trace($message){
  if(self::$_trace_enabled){
    $timestamp = self::microtime();
    $timestamp = substr($timestamp, 7, 7);
    $timestamp = number_format($timestamp, 2);

    $mem = round(memory_get_usage() / 1024 / 1024).'M';

    self::$stacktrace[count (self::$stacktrace).'|'.$timestamp.'|'.$mem] = $message;
  }
}

static function show_trace() {
  if (self::$_trace_enabled) {
    $le_keys = array_keys(self::$stacktrace);

    if (count($le_keys) > 0) {
      $first_key = $le_keys[0];
      $last_key = $le_keys[count($le_keys) - 1];

      $first_key_segments = explode('|', $first_key);
      $last_key_segments = explode('|', $last_key);

      $summary = array(
          'total' => $last_key_segments[0]
          , 'time' => number_format($last_key_segments[1] - $first_key_segments[1], 3) . 'sec'
          , 'memory' => $last_key_segments[2]
      );

      self::add_trace('trace compiled! summary: ' . json_encode($summary));
    } else {
      self::add_trace('zero trace. what are you trying to do?');
    }


    $final_trace = self::debug(self::$stacktrace);

    return $final_trace;
  }
  else
    return 'tracing disabled, enable via "self::enable_tracing();"';
}
<?php
/**
 * date range validator
 * 1. start must be less than end (2013-01-01 00:00:00 < 2013-01-02 00:00:00)
 * 2. the duration between start and end must be at most 2592000 seconds (30 days)
 * 
 * @param string $start (yyyy-mm-dd hh:mm:ss)
 * @param string $end   (yyyy-mm-dd hh:mm:ss)
 * @param assoc $rules override any preset rule
 * @param bool $strict if true, it will thrown an exception prior to the validation failure
 */
static function date_range_validator($start, $end, $rules = array(), $strict = false){
  $current_validation = true;

  $preset_rules = array(
      "duration" => 2592000
  );

  $timestart = strtotime($start);
  $timeend = strtotime($end);
  $computed = $timeend - $timestart;

  if($current_validation && $computed > $preset_rules["duration"]){
    $current_validation = false;
    if($strict) throw new Exception("date duration greater than ".$preset_rules["duration"]);
  }

  if($current_validation && $computed < 0){
    $current_validation = false;
    if($strict) throw new Exception("end date is less than start date");
  }
  
  return $current_validation;
}

/**
 * @param string $date_string
 * @param string $timezone_string eg:"Asia/Manila"
 * @param string $convert_to_timezone eg:"Asia/Manila"
 * @return \DateTime
 */
static function datetime($date_string, $timezone_string = null, $convert_to_timezone = null){
  if(is_null($timezone_string))
    $timezone_string = date_default_timezone_get();

  $timezone = new DateTimeZone($timezone_string);
  $datetime = new DateTime($date_string, $timezone);

  if(!is_null($convert_to_timezone)){
    $datetime->setTimezone(new DateTimeZone($convert_to_timezone));
  }

  return $datetime;
}

static function microtime() {
  list($usec, $sec) = explode(" ", microtime());
  return ((float) $usec + (float) $sec);
}

protected static $_timer_start_time = null;
protected static $_timer_started = false;

/**
 * returns current timelapse and clears timers
 * @return real
 */
static function timer_end() {
  if (self::$_timer_start_time == 0)
    return 0.00;

  $total = self::microtime() - self::$_timer_start_time;

  self::$_timer_start_time = null;
  self::$_timer_started = false;

  return number_format($total, 2);
}

/**
 *start the timer
 */
static function timer_start() {
  self::$_timer_start_time = self::microtime();
  self::$_timer_started = true;
}

/**
 * returns current timelapse
 * @return real
 */
static function timer_current(){
  if (self::$_timer_start_time == 0)
    return 0.00;

  $total = self::microtime() - self::$_timer_start_time;

  return number_format($total, 2);
}

/**
 * Return how long ago this was. eg: 3d 17h 4m 18s ago
 * @param  string $when date to challenge with gmdate
 * @return string
 */
static function ago($when){
  if(!strtotime($when))
    return null;

  // convert when to timestamp
  $when = date("U", strtotime($when));

  $diff = gmdate("U") - $when;

      // Days
  $day = floor($diff / 86400);
  $diff = $diff - ($day * 86400);

      // Hours
  $hrs = floor($diff / 3600);
  $diff = $diff - ($hrs * 3600);

      // Mins
  $min = floor($diff / 60);
  $diff = $diff - ($min * 60);

      // Secs
  $sec = $diff;

      // Return how long ago this was. eg: 3d 17h 4m 18s ago
      // Skips left fields if they aren't necessary, eg. 16h 0m 27s ago / 10m 7s ago
  $str = sprintf("%s%s%s%s",
    $day != 0 ? $day."d " : "",
    ($day != 0 || $hrs != 0) ? $hrs."h " : "",
    ($day != 0 || $hrs != 0 || $min != 0) ? $min."m " : "",
    $sec."s ago"
    );

  return $str;
}

/**
 * similar to util::ago except that it follows facebook style
 * and shows only the relevant ago to be seen: hour only | seconds only
 * @param  [type] $timestamp [description]
 * @return [type]            [description]
 */
function agoFB($timestamp){
  //type cast, current time, difference in timestamps
  $timestamp      = strtotime($timestamp);
  $current_time   = time();
  $diff           = $current_time - $timestamp;
  
  //intervals in seconds
  $intervals      = array (
    'year' => 31556926, 'month' => 2629744, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute'=> 60
    );
  
  //now we just find the difference
  if ($diff == 0)
  {
    return 'now';
  }    

  if ($diff < 60){
    return $diff == 1 
      ? $diff . " second" . ' ago' 
      : $diff . " seconds" . ' ago';
  }        

  if ($diff >= 60 && $diff < $intervals['hour']){
    $diff = floor($diff/$intervals['minute']);
    return $diff == 1 
      ? $diff . " minute" . ' ago' 
      : $diff . " minutes" . ' ago';
  }        

  if ($diff >= $intervals['hour'] && $diff < $intervals['day']){
    $diff = floor($diff/$intervals['hour']);
    return $diff == 1 
      ? $diff . " hour" . ' ago' 
      : $diff . " hours" . ' ago';
  }    

  if ($diff >= $intervals['day'] && $diff < $intervals['week']){
    $diff = floor($diff/$intervals['day']);
    return $diff == 1 
      ? $diff . " day" . ' ago' 
      : $diff . " days" . ' ago';
  }    

  if ($diff >= $intervals['week'] && $diff < $intervals['month']){
    $diff = floor($diff/$intervals['week']);
    return $diff == 1 
      ? $diff . " week" . ' ago' 
      : $diff . " weeks" . ' ago';
  }    

  if ($diff >= $intervals['month'] && $diff < $intervals['year']){
    $diff = floor($diff/$intervals['month']);
    return $diff == 1 
      ? $diff . " month" . ' ago' 
      : $diff . " months" . ' ago';
  }    

  if ($diff >= $intervals['year']){
    $diff = floor($diff/$intervals['year']);
    return $diff == 1 
      ? $diff . " year" . ' ago' 
      : $diff . " years" . ' ago';
  }
}
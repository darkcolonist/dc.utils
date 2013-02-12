<?php
/**
 * depends on: date.php
 */
static function log($dump, $name, $directory, $separate_per_date = true){
  // CHANGETHIS
  $logs_directory = APPPATH . "../../{$directory}/";

  if(!is_writable($logs_directory))
    return false;

  if(file_exists($logs_directory) == false)
    return;

  $dump = rtrim($dump, "\n");
  $dump = str_replace("\n", "\n\t", $dump);

  if($separate_per_date){
    $time_stamp = self::datetime(null, "Asia/Manila")->format("H:i:s");
    $date_file = self::datetime(null, "Asia/Manila")->format("Y-m-d");
    $file = "{$logs_directory}{$name}-{$date_file}.txt";
  }
  else{
    $time_stamp = self::datetime(null, "Asia/Manila")->format("m-d H:i:s");
    $file = "{$logs_directory}{$name}.txt";
  }

  $mem = round(memory_get_usage() / 1024 / 1024).'M';
  $time_stamp .= '|'.$mem;

  if(self::$_timer_started){
    $time_stamp .= "|".self::timer_current()."s";
  }

  $dump = "[{$time_stamp}]\t{$dump}";

  $file_data = "\n$dump";

  if(file_exists($file)){
    $fh = fopen($file, 'a') or die("can't open file");
  }else{
    $fh = fopen($file, 'w') or die("can't open file");
    $file_data = ltrim($file_data, "\n");
  }

  fwrite($fh, $file_data);
  fclose($fh);

  return true;
}

/**
 * logs in csv format:
 * timestamp, memory, timelapse, [<assoc_array_merge> $dump]
 *
 * @param assoc $dump
 * @param string $name
 * @param string $directory
 * @param boolean $separate_per_date
 * @return boolean
 */
static function logcsv($dump, $name, $directory, $separate_per_date = true){
  // CHANGETHIS
  $logs_directory = APPPATH . "../../{$directory}/";

  if(!is_writable($logs_directory))
    return false;

  if(file_exists($logs_directory) == false)
    return;

  if($separate_per_date){
    $time_stamp = self::datetime(null, "Asia/Manila")->format("H:i:s");
    $date_file = self::datetime(null, "Asia/Manila")->format("Y-m-d");
    $file = "{$logs_directory}{$name}-{$date_file}.csv";
  }
  else{
    $time_stamp = self::datetime(null, "Asia/Manila")->format("Y-m-d H:i:s");
    $file = "{$logs_directory}{$name}.csv";
  }

  $mem = round(memory_get_usage() / 1024 / 1024);

  $primary_dump = array(
      $time_stamp // date
      , $mem      // memory
      , self::$_timer_started
          ? self::timer_current()
          : 0.00  // timelapse
  );

  $dump = array_merge($primary_dump, $dump);

  if(file_exists($file)){
    $fh = fopen($file, 'a') or die("can't open file");
  }else{
    $fh = fopen($file, 'w') or die("can't open file");
    // header
    fputcsv($fh, array("date", "memory", "timelapse"));
  }
  
  fputcsv($fh, $dump);
  fclose($fh);

  return true;
}
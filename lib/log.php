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

/**
 * logs message to file
 *
 * indicate base directory in the beginning, if the base directory 
 * does not exist, logging will not take place.
 * 
 * @param  [type]  $message log contents
 * @param  [type]  $name    folder with name will be created under
 *                          base directory
 * @param  integer $limit   limit of files to keep in the $name
 *                          folder
 * @return [type]           complete filename of the log
 */
static function log2dir($message, $name, $limit = 10){
  // CHANGETHIS add a checker also if you wish
  //            to log only if logging is enabled
  //            in config
  // CHANGETHIS directory to your preference
  $base_directory = APPPATH."logs_custom";

  if(!is_dir($base_directory) || !is_writable($base_directory)){
    // base directory doesn't exist; logging disabled
    return false;
  }

  $log_directory = $base_directory . "/" . $name;

  if(!is_dir($log_directory)){
    // create directory
    mkdir($log_directory, 0777);
  }

  $filename = gmdate("Y-m-d").".txt";

  $filename = $log_directory . "/" . $filename;

  $mem = round(memory_get_usage() / 1024 / 1024).'M';
  $timestamp = gmdate("H:i:s").'|'.$mem;

  $log = "[{$timestamp}]\t".$message."\n";

  if(file_exists($filename)){
    $fh = fopen($filename, 'a') or die("can't open file");
  }else{
    $fh = fopen($filename, 'w') or die("can't open file");
    $log = ltrim($log, "\n");
  }
  fwrite($fh, $log);
  fclose($fh);

  // delete old files and keep only the last $limit files in this 
  // directory
  $dir = opendir($log_directory);
  $list = array();
  while($file = readdir($dir)){
    $file_info = pathinfo($file);

    if (isset($file_info['extension']) and $file_info['extension'] == "txt"){
      // add the filename, to be sure not to
      // overwrite a array key
      $mtime = filemtime($log_directory ."/". $file) . ',' . $file;
      $list[$mtime] = $file;
    }
  }
  closedir($dir);
  krsort($list);

  $counter = 1;
  foreach ($list as $fkey => $fvalue) {
    if($counter > $limit){
      unlink($log_directory."/".$fvalue);
    }

    $counter ++;
  }

  return $filename;
}

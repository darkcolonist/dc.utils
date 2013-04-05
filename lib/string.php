<?php
/**
 * sanitize a string
 * levels:
 * 0 = default, just trim spaces
 * 1 = advanced, convert special characters to html entities (default)
 * 2 = expert-enhanced, only retain specific characters in REGEXP
 * 3 = expert-mysql, filter also for mysql queries
 *
 * @param string $data
 * @param int $level
 * @return string
 */
static function sanitize($data, $level = 1){

  //remove spaces from the input
  $data = trim($data);

  //convert special characters to html entities
  if($level > 0){
    $data = str_replace("<br>", "\n", $data);
    $data = str_replace("<br />", "\n", $data);
    $data = str_replace("<br/>", "\n", $data);
    $data = strip_tags($data, "<img><a>");
    $data = htmlspecialchars($data);
    $data = nl2br($data);
  }

  // Remove all unwanted chars. Keep only the ones listed
  if($level > 1) $data = preg_replace('/[^A-Za-z0-9]/is', '', $data );

  //sanitize before using any MySQL database queries
  if($level > 2) $data = mysql_real_escape_string($data);
  
  return $data;
}

static function get_truncated_string($string, $length){
  $val = "<span style='
    width: {$length}px;
    text-overflow: ellipsis;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;' title='\"{$string}\"'>{$string}</span>";

  return $val;
}
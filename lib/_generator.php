<?php
/**
 * @title: Util Generator File
 * @description: This file generates the Util for testing and implementation
 * @author: christian noel reyes <me@misty-stix.com>
 * @date: 2013-02-19
 */
$dir = dirname(__FILE__);
$core = file_get_contents($dir . "/Util.php");

$segments = "";
$files = glob($dir . '/*.php');
foreach ($files as $file) {
  if(substr_count($file, "Util.php") == 0 &&
     substr_count($file, "_generator.php") == 0)
    $segments .= file_get_contents($file);
}

$code = str_replace("// [placeholder]", $segments, $core);
$code = str_replace("<?php", "", $code);

eval($code);
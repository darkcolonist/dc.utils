<?php
/**
 * @title: Util Generator File
 * @description: This file generates the Util for testing and implementation. It
 *  also creates a file named _temp.php in the lib folder which will serve as
 *  the compiled version.
 * @author: christian noel reyes <me@misty-stix.com>
 * @date: 2013-02-19
 */
$dir = dirname(__FILE__);
$core = file_get_contents($dir . "/Util.php");

$segments = "";
$header = "";
$files = glob($dir . '/*.php');
foreach ($files as $file) {
  if(substr_count($file, "Util.php") == 0 &&
     substr_count($file, "_generator.php") == 0 &&
     substr_count($file, "_temp.php") == 0)
  {
    $header .= "// loaded: ".$file."\n";
    $segments .= file_get_contents($file);
  }
}

$code = str_replace("// [placeholder]", $segments, $core);
$code = str_replace("<?php", "", $code);
$code = str_replace("?>", "", $code);

$code = "\n".$header.$code;

$message = "// this is an auto-generated code, modifying this file will not";
$message .= " have any effect on the system.";

file_put_contents($dir . "/_temp.php", "<?php ".$message." ".$code."?>");

eval($code);
<?php
/**
 * compress a list of files
 * @param assoc $files
 * @param string $zipname
 */
static function zip($files, $zipname = "./file.zip"){
  $zip = new ZipArchive;
  $zip->open($zipname, ZipArchive::CREATE);
  foreach ($files as $file) {
    $temp = explode("/", $file);
    $localname = $temp[count($temp)-1];
    $zip->addFile($file, $localname);
  }
  $zip->close();

  return $zipname;
}
<?php
public static function get_revision(){
  $entries_fn = APPPATH."../../.svn/entries";

  $revision = null;

  if(file_exists($entries_fn)){
    $version = file($entries_fn);

    $revision_string = "&nbsp;r";

    if(is_array($version) && isset($version[3])){
      $revision = $revision_string.$version[3];
    }else if(is_array($version) && !isset($version[3])){
      $revision = $revision_string.$version[0];
    }else{
      $revision = $revision_string.$version;
    }

  }

  return $revision;
}
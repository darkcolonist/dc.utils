<?php
  require_once("../lib/Util.php");
  
  Util::enable_tracing();
  
  Util::add_trace("something written");
  Util::add_trace("something done");
  Util::add_trace("something forgotten");
  Util::add_trace("something remembered");
  Util::add_trace("something started");
  Util::add_trace("something ended");
  Util::add_trace("something completed");
  
  echo Util::show_trace();
?>
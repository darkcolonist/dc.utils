<?php
static function debug($variable) {
  return '<pre>' . print_r($variable, true) . '</pre>';
}
<?php
static function format($orig_amount, $show_currency = true){
  if($orig_amount == null)
    $orig_amount = 0.00;

  $orig_amount = str_replace(",", "", $orig_amount); // remove the ',' char

  $amount = $orig_amount;

  if($orig_amount < 0)
    $amount = $orig_amount * -1;

  $amount = number_format($amount, 2);

  if($show_currency)
    $amount = "PHP ".$amount;
  
  if($orig_amount < 0)
    $amount = "({$amount})";

  return $amount;
}
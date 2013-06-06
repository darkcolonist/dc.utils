<?php
static $trace = array();
/**
 * check if $ip matches (or is in scope of) $network
 *
 * eg.
 * match | ip | result
 * 192.168.1.1 | 192.168.0.1 | false
 * 192.168.*.* | 192.168.0.1 | true
 *
 * @param type $network
 * @param type $ip
 * @return boolean
 */
static function match($network, $ip) {
  $network=trim($network);
  $orig_network = $network;
  $ip = trim($ip);
  if ($ip == $network) {
      self::$trace[] = "used network ($network) for ($ip)\n";
      return TRUE;
  }
  $network = str_replace(' ', '', $network);
  if (strpos($network, '*') !== FALSE) {
      if (strpos($network, '/') !== FALSE) {
          $asParts = explode('/', $network);
          $network = @ $asParts[0];
      }
      $nCount = substr_count($network, '*');
      $network = str_replace('*', '0', $network);
      if ($nCount == 1) {
          $network .= '/24';
      } else if ($nCount == 2) {
          $network .= '/16';
      } else if ($nCount == 3) {
          $network .= '/8';
      } else if ($nCount > 3) {
          return TRUE; // if *.*.*.*, then all, so matched
      }
  }

  self::$trace[] = "from original network($orig_network), used network ($network) for ($ip)\n";

  $d = strpos($network, '-');
  if ($d === FALSE) {
      $ip_arr = explode('/', $network);
      if (!preg_match("@\d*\.\d*\.\d*\.\d*@", $ip_arr[0], $matches)){
          $ip_arr[0].=".0";    // Alternate form 194.1.4/24
      }

      $network_long = ip2long($ip_arr[0]);
      if(isset($ip_arr[1])){
        $x = ip2long($ip_arr[1]);
        $mask = long2ip($x) == $ip_arr[1] ? $x : (0xffffffff << (32 - $ip_arr[1]));
        $ip_long = ip2long($ip);
        return ($ip_long & $mask) == ($network_long & $mask);
      }else{
        return false;
      }
  } else {
      $from = trim(ip2long(substr($network, 0, $d)));
      $to = trim(ip2long(substr($network, $d+1)));
      $ip = ip2long($ip);
      return ($ip>=$from and $ip<=$to);
  }
}

/**
 * check if $ip matches (or is in scope of) any $network in $network_arr
 * @param array $network_arr
 * @param type $ip
 */
static function lookup($network_arr, $ip){
  if(count($network_arr) == 0)
    return false;

  foreach($network_arr as $network){
    $result = self::match($network, $ip);

    if($result)
      return true;
  }

  return false;
}
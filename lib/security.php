<?php
 /**
   * checks the strength of the password
   * @param  string $password
   * @return int           0-5 password strength (5 = strongest)
   */
  static $passwordStrengthLog = array();
  static function passwordStrength($password){
    $strength = 0;

    preg_match_all('/[A-Z]/', $password, $result);
    $strength += count($result[0]) > 0 ? 1 : 0;
    if(count($result[0])){
      self::$passwordStrengthLog[] = "upper case passed";
    }

    preg_match_all('/[a-z]/', $password, $result);
    $strength += count($result[0]) > 0 ? 1 : 0;
    if(count($result[0])){
      self::$passwordStrengthLog[] = "lower case passed";
    }

    /*** get the numbers in the password ***/
    preg_match_all('/[0-9]/', $password, $numbers);
    $strength += count($numbers[0]) > 0 ? 1 : 0;
    if(count($numbers[0])){
      self::$passwordStrengthLog[] = "numeric passed";
    }

    /*** check for special chars ***/
    preg_match_all('/[!@#$%&*?-]/', $password, $specialchars);
    $strength += count($specialchars[0]) > 0 ? 1 : 0;
    if(count($specialchars[0])){
      self::$passwordStrengthLog[] = "special characters passed";
    }

    /*** get the number of unique chars ***/
    $chars = str_split($password);
    $num_unique_chars = count( array_unique($chars) );
    $strength += ($num_unique_chars >= 4) ? 1 : 0;
    self::$passwordStrengthLog[] = "total unique characters: ".$num_unique_chars;

    self::$passwordStrengthLog[] = "final computed: ".$strength;

    return $strength;
  }

  static function generateStrongPassword($length = 10, $add_dashes = true, $available_sets = 'luds'){
    $sets = array();
    if(strpos($available_sets, 'l') !== false)
      $sets[] = 'abcdefghjkmnpqrstuvwxyz';
    if(strpos($available_sets, 'u') !== false)
      $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    if(strpos($available_sets, 'd') !== false)
      $sets[] = '23456789';
    if(strpos($available_sets, 's') !== false)
      $sets[] = '!@#$%&*?';

    $all = '';
    $password = '';
    foreach($sets as $set)
    {
      $password .= $set[array_rand(str_split($set))];
      $all .= $set;
    }

    $all = str_split($all);
    for($i = 0; $i < $length - count($sets); $i++)
      $password .= $all[array_rand($all)];

    $password = str_shuffle($password);

    if(!$add_dashes)
      return $password;

    $dash_len = floor(sqrt($length));
    $dash_str = '';
    while(strlen($password) > $dash_len)
    {
      $dash_str .= substr($password, 0, $dash_len) . '-';
      $password = substr($password, $dash_len);
    }
    $dash_str .= $password;
    return $dash_str;
  }

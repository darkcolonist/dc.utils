<?php
/**
 *
 * sample input for $haystack:
 * array(
 *  0 => array(       .: test 1:.        .: test 2:.
 *    "u_id" => 5,	  $v1_name : u_id	   $v1_name : u_id
 *    "p_id" => 10	  $v2_name : p_id	   $v2_name : p_id
 *  ),                $v1 : 7            $v1 : 8
 *  1 => array(       $v2 : 10	         $v2 : 10
 *    "u_id" => 6,	  return: true	     return: false
 *    "p_id" => 10
 *  ),
 *  2 => array(
 *    "u_id" => 7,
 *    "p_id" => 10
 *  )
 * )
 *
 * @param assoc $haystack
 * @param string $v1_name
 * @param string $v1
 * @param string $v2_name
 * @param string $v2
 * @return boolean
 */
static function lookup($haystack, $v1_name, $v1, $v2_name, $v2){
  if(count($haystack) == 0)
    return false;

  foreach($haystack as $haystrand){
    if(isset($haystrand[$v1_name]) && isset($haystrand[$v2_name]))
      if($haystrand[$v1_name] == $v1 && $haystrand[$v2_name] == $v2)
        return true;
  }
  return false;
}

/**
 * unset unwanted array keys
 *
 * removes all other keys in the $array except the ones in $keep_keys
 *
 * @param assoc $result_array
 * @param assoc $keep_keys
 */
static function remove_array_indices($result_array, $keep_keys){
  foreach($result_array as $key => $result_item){
    $new_result_item = array();
    foreach($keep_keys as $the_key){
      $new_result_item[$the_key] = $result_item[$the_key];
    }
    $result_array[$key] = $new_result_item;
  }

  return $result_array;
}

/**
 * Translate a result array into a HTML table
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.3.2
 * @link        http://aidanlister.com/2004/04/converting-arrays-to-human-readable-tables/
 * @param       array  $array      The result (numericaly keyed, associative inner) array.
 * @param       bool   $recursive  Recursively generate tables for multi-dimensional arrays
 * @param       string $null       String to output for blank cells
 *
 * @author      Christian Noel Reyes <me@misty-stix.com>
 * @version     1.3.2.1
 *
 * Added callbacks["column"]("scope : callback function")
 */
static function array2table($array, $recursive = false, $null = '&nbsp;', $callbacks = array()) {
  // Sanity check
  if (empty($array) || !is_array($array)) {
    return false;
  }

  if (!isset($array[0]) || !is_array($array[0])) {
    $array = array($array);
  }

  // Start the table
  $table = "<table>\n";

  // The header
  $table .= "\t<tr>";
  // Take the keys from the first row as the headings
  foreach (array_keys($array[0]) as $heading) {
    $table .= '<th>' . $heading . '</th>';
  }
  $table .= "</tr>\n";

  // The body
  foreach ($array as $row) {
    $table .= "\t<tr>";
    foreach ($row as $rkey => $cell) {
      $table .= '<td>';

      // Cast objects
      if (is_object($cell)) {
        $cell = (array) $cell;
      }

      if ($recursive === true && is_array($cell) && !empty($cell)) {
        // Recursive mode
        $table .= "\n" . self::array2table($cell, true, true) . "\n";
      } else {
        if(isset($callbacks[$rkey])
                && is_callable($callbacks[$rkey])){
          eval("\$table .= $callbacks[$rkey](\"".htmlspecialchars($cell)."\");");
        }else{
          $table .= (strlen($cell) > 0) ?
                  htmlspecialchars((string) $cell) :
                  $null;
        }
      }

      $table .= '</td>';
    }

    $table .= "</tr>\n";
  }

  $table .= '</table>';
  return $table;
}

/**
 * presents a two-dimensIONal array into a table
 *
 * converts:
 * {
 *   "name" : "cris",
 *   "age" : 18,
 *   "gender" : "male"
 * }
 *
 * into:
 * <table class="tbl-data">
 * 	<tr><td class="row-label">name</td><td class="row-value">cris</td></tr>
 * 	<tr><td class="row-label">age</td><td class="row-value">18</td></tr>
 * 	<tr><td class="row-label">gender</td><td class="row-value">male</td></tr>
 * </table>
 *
 * var $callbacks:
 * - "field" => "callback"
 * - do some additional functions to field using predefined callback
 * - there must be an existing function "callback"
 * - eg: "name" => "Util::format_name"
 * - process: Util::format_name("name")
 *
 * var $options: (classes, defaults)
 * - table: "tbl-data"
 * - label: "row-label"
 * - value: "row-value"
 *
 * var $omit:
 * - fields to skip
 */
static function tablify_array($arr, $callbacks = array(), $options = array(), $omit = array()) {
  $final_options = array(
      "table" => empty($options["table"]) ? "tbl-data" : $options["table"]
      , "label" => empty($options["label"]) ? "row-label" : $options["label"]
      , "value" => empty($options["value"]) ? "row-value" : $options["value"]
  );

  if(count($arr) == 0)
    return 1;

  $tablified = "";
  $tablified .= "<table class='{$final_options["table"]}'>";

  foreach($arr as $key => $value){
    $result = array_search($key, $omit);

    if(array_search($key, $omit) !== false) continue;
    
    $tablified .= "<tr>";

      if(count($callbacks) > 0){
        if(!empty($callbacks[$key])){
          if(!is_callable($callbacks[$key])) return "error: function {$callbacks[$key]} is not callable.";
          eval("\$value = $callbacks[$key](\"".htmlspecialchars($value)."\");");
        }
      }

      $tablified .= "<td class='{$final_options["label"]}'>";
      $tablified .= $key;
      $tablified .= "</td>";

      $tablified .= "<td class='{$final_options["value"]}'>";

      if(is_array($value)){
        $tablified .= self::tablify_array($value, $callbacks, $options, $omit);
      }else{
        $tablified .= $value;
      }

      $tablified .= "</td>";
      
    $tablified .= "</tr>";
  }

  $tablified .= "</table>";

  return $tablified;
}

/**
 * converts an array into an sql IN parameter
 * input:
 * $arr = array(
 *     "\"val 1\"",
 *     "v3",
 *     "value4",
 *     5,
 *     "six"
 * );
 *
 * output:
 * ("\"val 1\"","v3","value4","5","six")
 *
 * can be used in:
 * select * from table where name in ("\"val 1\"","v3","value4","5","six")
 *
 * @param assoc $arr
 * @return string
 */
static function implode_sqlin($arr){
  if(!is_array($arr) || count($arr) == 0) return false;

  $converted = "";
  foreach($arr as $value){
    $value = str_replace("\"", "\\\"", $value);
    $converted .= "\"{$value}\",";
  }
  $converted = rtrim($converted, ",");
  $converted = "({$converted})";

  return $converted;
}

/**
 * create a dropdown-prepared key-value array for commonly used frameworks
 * @param assoc $arr
 * @param string $null
 * @return assoc
 */
static function to_dropdown_key_val($arr, $null = "- select -"){
  $dd = array();

  if($null != null)
    $dd[null] = $null;

  foreach($arr as $key => $val){
    $dd[$key] = $val;
  }

  return $dd;
}
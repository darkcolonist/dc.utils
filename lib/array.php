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

/**
 * Generatting CSV formatted string from an array.
 * By Sergey Gurevich.
 */
static function array_to_csv($array, $header_row = true, $col_sep = ",", $row_sep = "\n", $qut = '"')
{
  if (!is_array($array) or !is_array($array[0])) return false;

  $output = "";

  //Header row.
  if ($header_row)
  {
    foreach ($array[0] as $key => $val)
    {
      //Escaping quotes.
      $key = str_replace($qut, "$qut$qut", $key);
      $output .= "$col_sep$qut$key$qut";
    }
    $output = substr($output, 1)."\n";
  }
  //Data rows.
  foreach ($array as $key => $val)
  {
    $tmp = '';
    foreach ($val as $cell_key => $cell_val)
    {
      //Escaping quotes.
      $cell_val = str_replace($qut, "$qut$qut", $cell_val);
      $tmp .= "$col_sep$qut$cell_val$qut";
    }
    $output .= substr($tmp, 1).$row_sep;
  }

  return $output;
}

/**
 * search contents of a multi-dimensional array
 * 
 * source: http://stackoverflow.com/questions/6661530/php-multi-dimensional-array-search
 * @param array $array
 * @param string $index_name
 * @param string $needle
 * @return null or key 
 */
static function array_multi_dimensional_search($array, $index_name, $needle) {
  foreach ($array as $key => $val) {
    if (is_array($val[$index_name])){
      return self::array_multi_dimensional_search($val[$index_name], $index_name, $needle);
    }else{
      if ($val[$index_name] === $needle) {
        return $key;
      }
    }
  }
  return null;
}

/**
 * array to sql value 'in' imploder, used primarily as a callback for
 * array_walk php native function
 * 
 * example:
 *  array_walk($batch_hashes, array("Util", "stringify_array_values_to_sql"));
 * 
 * converts: array(1,2,3)
 * into: array("\"1\"","\"2\"","\"3\"")
 * 
 * @param string $value
 * @param string $key 
 */
static function stringify_array_values_to_sql(&$value, $key){
  $value = "\"{$value}\"";
}

/**
 * converts an associative array to a csv, using keys as headers
 *
 * sample array: 
 * $arr = array(
 *        array(
 *          "name" => "christian"
 *          , "color" => "silver"
 *          , "car" => "honda"
 *          , "model" => "eg"
 *        )
 *        , array(
 *          "name" => "donnie"
 *          , "car" => "honda"
 *        )
 *        , array(
 *          "name" => "yabs"
 *          , "car" => "toyota"
 *          , "year" => "1992"
 *        )
 *        , array(
 *          "name" => "jen"
 *          , "tranny" => "auto"
 *          , "car" => "mitsubishi"
 *          , "year" => "2000"
 *          , "model" => "gta"
 *        )
 *    );
 */
static function assoc_array_to_csv($arr, $render = false){
  $size_mb = 10;
  
  $temp_limit = $size_mb * 1024 * 1024;
  $handle = fopen("php://temp/maxmemory:{$temp_limit}", "r+");
  
  // <editor-fold defaultstate="collapsed" desc="magic | input: arr | output: headers, flattened">
  $headers = array();
  $flattened = array();
  foreach($arr as $key => $tmp){
    foreach($tmp as $field => $value){
      $field = strtolower($field);
      if(!in_array($field, $headers)){
        $headers[] = $field;
      }
    }
    
    foreach($headers as $header){
        if(isset($tmp[$header]))
          $flattened[$key][] = $tmp[$header];
        else
          $flattened[$key][] = "";
      }
  }
  // </editor-fold> 
  
  fputcsv($handle, $headers);
  
  foreach($flattened as $row){
    fputcsv($handle, $row);
  }
  
  rewind($handle);
  
  $data = stream_get_contents($handle);
  
  fclose($handle);
  
  if($render){
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename='.date('YmdHis').'export.csv');

    echo $data;
  }
  else
      return $data;
}
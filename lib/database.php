<?php
/**
 * create a backup of all tables specifed
 * @param string $host
 * @param string $user
 * @param string $pass
 * @param string $name
 * @param string $tables "table1,table2,table3"
 * @param string $path "./files/"
 * @return assoc file list
 */
static function backup_tables($host, $user, $pass, $name, $tables = '*', $path = "./")
{
  $files = array();

  $link = mysql_connect($host,$user,$pass);
  $hash_core = substr(md5(uniqid()), 0, 8);
  $hash = "[".$hash_core."]";
  $files["hash"] = $hash_core;
  mysql_select_db($name,$link);

  $nested_path = $path."{$hash}/";
  mkdir($nested_path);

  //get all of the tables
  if($tables == '*')
  {
    $tables = array();
    $result = mysql_query('SHOW TABLES');
    while($row = mysql_fetch_row($result))
    {
      $tables[] = $row[0];
    }
  }
  else
  {
    $tables = is_array($tables) ? $tables : explode(',',$tables);
  }

  $return = "";
  //cycle through
  foreach($tables as $table)
  {
    $result = mysql_query('SELECT * FROM '.$table);
    $num_fields = mysql_num_fields($result);

    $return.= 'DROP TABLE '.$table.';';
    $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
    $return.= "\n\n".$row2[1].";\n\n";

    for ($i = 0; $i < $num_fields; $i++)
    {
      while($row = mysql_fetch_row($result))
      {
        $return.= 'INSERT INTO '.$table.' VALUES(';
        for($j=0; $j<$num_fields; $j++)
        {
          $row[$j] = addslashes($row[$j]);

          $row[$j] = str_replace("\n", "\\n", $row[$j]);
          
          if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
          if ($j<($num_fields-1)) { $return.= ','; }
        }
        $return.= ");\n";
      }
    }

    //save file
    $filename = $nested_path.$name.'.'.$table.'.sql';
    $handle = fopen($filename,'w+');
    $files["list"][] = $filename;
    fwrite($handle,$return);
    fclose($handle);

    $return = "";
  }

  return $files;
}
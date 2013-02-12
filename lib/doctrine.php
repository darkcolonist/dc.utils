<?php
static $doctrine_profiler;
/**
 * initializes a doctrine profiler instance
 * @return Doctrine_Connection_Profiler
 */
static function doctrine_profiler_begin() {
  self::$doctrine_profiler = new Doctrine_Connection_Profiler();
  $conn = Doctrine_Manager::connection();
  $conn->setListener(self::$doctrine_profiler);

  return self::$doctrine_profiler;
}

/**
 * ends the doctrine profiler and returns tabular representation of the
 * profiler.
 * note: you need to run self::doctrine_profiler_begin first before
 *  self::doctrine_profiler_end
 * @return boolean|string
 */
static function doctrine_profiler_end() {
  if(empty(self::$doctrine_profiler))
    return false;

  $time = 0;

  $doctrine_dbg = "<table border='1' style='border-collapse: collapsed; font-size: 60%;'>";

  $doctrine_dbg .= "
    <tr>
      <th>Name</th>
      <th>Time</th>
      <th>Query</th>
      <th>Params</th>
    </tr>
  ";

  $num_executes = 0;

  foreach (self::$doctrine_profiler as $event) {
    $doctrine_dbg .= "<tr>";

    $time += $event->getElapsedSecs();
    $doctrine_dbg .= "<td>" . $event->getName() . "</td>";
    if ($event->getName() == "execute") {
      $doctrine_dbg .= "<td><strong style='font-size: 150%; color: red; text-decoration: underline;'>" . sprintf("%f", $event->getElapsedSecs()) . "</strong></td>";
      $num_executes += 1;
    } else {
      $doctrine_dbg .= "<td>" . sprintf("%f", $event->getElapsedSecs()) . "</td>";
    }
    $doctrine_dbg .= "<td><textarea rows='5' cols='100' style='font-size: 100%'>" . $event->getQuery() . "</textarea></td>";
    $params = $event->getParams();
    if (!empty($params)) {
      $doctrine_dbg .= "<td>" . print_r($params, true) . "</td>";
    } else {
      $doctrine_dbg .= "<td>n/a</td>";
    }

    $doctrine_dbg .= "</tr>";
  }

  $doctrine_dbg .= "</table>";

  $doctrine_dbg .= "Executes: {$num_executes}<br/>";

  $doctrine_dbg .= "Total time: " . $time . "<br/>";

  return $doctrine_dbg;
}
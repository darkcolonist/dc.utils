<?php
require_once("../lib/_generator.php");

$values = array(
    "sss" => array(
        12999,
        3221,
        1200,
        5000,
        6590,
        18000
    ),
    "philhealth" => array(
        2300,
        12000,
        350000,
        25000,
        50000,
        11000,
        77700,
    ),
    "bir" => array(
        array("gross" => 99232, "status" => "Single", "dependents" => 3),
        array("gross" => 33221, "status" => "Married", "dependents" => 2),
        array("gross" => 15213, "status" => "Married", "dependents" => 10),
        array("gross" => 13000, "status" => "Married", "dependents" => 10),
        array("gross" => 14000, "status" => "Married", "dependents" => 3),
        array("gross" => 17000, "status" => "Single", "dependents" => 0),
        array("gross" => 32000, "status" => "Single", "dependents" => 0),
        array("gross" => 120000, "status" => "Single", "dependents" => 0),
        array("gross" => 90000, "status" => "Single", "dependents" => 0),
        array("gross" => 85000, "status" => "Married", "dependents" => 10),
    )
);

$disp = array();

foreach($values["sss"] as $s_key => $item){
  $computed = Util::tax_compute_sss($item);

  $disp["sss"][] = array(
      "given" => $item,
      "computed-er" => $computed["er"],
      "computed-ee" => $computed["ee"]
  );
}

foreach($values["philhealth"] as $s_key => $item){
  $computed = Util::tax_compute_philhealth($item);

  $disp["philhealth"][] = array(
      "given" => $item,
      "computed" => $computed
  );
}

foreach($values["bir"] as $s_key => $item){
  $computed_sss = Util::tax_compute_sss($item["gross"]);
  $computed_philhealth = Util::tax_compute_philhealth($item["gross"]);

  $disp["bir"][] = array(
      "given" => $item,
      "computed_sss_er" => $computed_sss["er"],
      "computed_sss_ee" => $computed_sss["ee"],
      "computed_philhealth" => $computed_philhealth,
      "computed_bir" => Util::tax_compute_bir(
              $item["gross"], $computed_sss["ee"], $computed_philhealth, 0,
              $item["status"], $item["dependents"])
  );
}

$disp_str = Util::array2table($disp, true);

echo $disp_str;
?>
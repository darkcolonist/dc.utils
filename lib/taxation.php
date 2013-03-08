<?php
static function tax_compute_sss($salary) {
  $ec = 10;

  switch ($salary) {
    case (is_null($salary)):
      $er = 0;
      $ee = 0;
      break;
    case ($salary >= 1000 && $salary < 1250):
      $er = 70.70 + $ec;
      $ee = 33.30;
      break;
    case ($salary >= 1250 && $salary < 1750):
      $er = 106 + $ec;
      $ee = 50;
      break;
    case ($salary >= 1750 && $salary < 2250):
      $er = 141.30 + $ec;
      $ee = 66.70;
      break;
    case ($salary >= 2250 && $salary < 2750):
      $er = 176.70 + $ec;
      $ee = 83.30;
      break;
    case ($salary >= 2750 && $salary < 3250):
      $er = 212 + $ec;
      $ee = 100;
      break;
    case ($salary >= 3250 && $salary < 3750):
      $er = 247.30 + $ec;
      $ee = 116.70;
      break;
    case ($salary >= 3750 && $salary < 4250):
      $er = 282.70 + $ec;
      $ee = 133.30;
      break;
    case ($salary >= 4250 && $salary < 4750):
      $er = 318 + $ec;
      $ee = 150;
      break;
    case ($salary >= 4750 && $salary < 5250):
      $er = 353.30 + $ec;
      $ee = 166.70;
      break;
    case ($salary >= 5250 && $salary < 5750):
      $er = 388.70 + $ec;
      $ee = 183.30;
      break;
    case ($salary >= 5750 && $salary < 6250):
      $er = 424 + $ec;
      $ee = 200;
      break;
    case ($salary >= 6250 && $salary < 6750):
      $er = 459.30 + $ec;
      $ee = 216.70;
      break;
    case ($salary >= 6750 && $salary < 7250):
      $er = 494.70 + $ec;
      $ee = 233.30;
      break;
    case ($salary >= 7250 && $salary < 7750):
      $er = 530 + $ec;
      $ee = 250;
      break;
    case ($salary >= 7750 && $salary < 8250):
      $er = 565.30 + $ec;
      $ee = 266.70;
      break;
    case ($salary >= 8250 && $salary < 8750):
      $er = 600.70 + $ec;
      $ee = 283.30;
      break;
    case ($salary >= 8750 && $salary < 9250):
      $er = 636 + $ec;
      $ee = 300;
      break;
    case ($salary >= 9250 && $salary < 9750):
      $er = 671.30 + $ec;
      $ee = 316.70;
      break;
    case ($salary >= 9750 && $salary < 10250):
      $er = 706.70 + $ec;
      $ee = 333.30;
      break;
    case ($salary >= 10250 && $salary < 10750):
      $er = 742 + $ec;
      $ee = 350;
      break;
    case ($salary >= 10750 && $salary < 11250):
      $er = 777.30 + $ec;
      $ee = 366.70;
      break;
    case ($salary >= 11250 && $salary < 11750):
      $er = 812.70 + $ec;
      $ee = 383.30;
      break;
    case ($salary >= 11750 && $salary < 12250):
      $er = 848 + $ec;
      $ee = 400;
      break;
    case ($salary >= 12250 && $salary < 12750):
      $er = 883.30 + $ec;
      $ee = 416.70;
      break;
    case ($salary >= 12750 && $salary < 13250):
      $er = 918.70 + $ec;
      $ee = 433.30;
      break;
    case ($salary >= 13250 && $salary < 13750):
      $er = 954 + $ec;
      $ee = 450;
      break;
    case ($salary >= 13750 && $salary < 14250):
      $er = 989.30 + $ec;
      $ee = 466.70;
      break;
    case ($salary >= 14250 && $salary < 14750):
      $er = 1024.70 + $ec;
      $ee = 483.30;
      break;
    case ($salary >= 14750):
      $er = 1060 + 30;
      $ee = 500;
      break;
    default:
      $er = 0;
      $ee = 0;
      break;
  }

  $sss_array = array
      (
      'er' => $er,
      'ee' => $ee,
  );

  return $sss_array;
}

static function tax_compute_philhealth($salary) {
  switch ($salary) {
    case (is_null($salary)):
      $phil_health = 0;
    break;
    case ($salary >= 1 && $salary < 8000):
      $phil_health = 87.5;
    break;
    case ($salary >= 8000 && $salary < 9000):
      $phil_health = 100;
    break;
    case ($salary >= 9000 && $salary < 10000):
      $phil_health = 112.5;
    break;
    case ($salary >= 10000 && $salary < 11000):
      $phil_health = 125;
    break;
    case ($salary >= 11000 && $salary < 12000):
      $phil_health = 137.5;
    break;
    case ($salary >= 12000 && $salary < 13000):
      $phil_health = 150;
    break;
    case ($salary >= 13000 && $salary < 14000):
      $phil_health = 162.5;
    break;
    case ($salary >= 14000 && $salary < 15000):
      $phil_health = 175;
    break;
    case ($salary >= 15000 && $salary < 16000):
      $phil_health = 187.5;
    break;
    case ($salary >= 16000 && $salary < 17000):
      $phil_health = 200;
    break;
    case ($salary >= 17000 && $salary < 18000):
      $phil_health = 212.5;
    break;
    case ($salary >= 18000 && $salary < 19000):
      $phil_health = 225;
    break;
    case ($salary >= 19000 && $salary < 20000):
      $phil_health = 237.5;
    break;
    case ($salary >= 20000 && $salary < 21000):
      $phil_health = 250;
    break;
    case ($salary >= 21000 && $salary < 22000):
      $phil_health = 262.5;
    break;
    case ($salary >= 22000 && $salary < 23000):
      $phil_health = 275;
    break;
    case ($salary >= 23000 && $salary < 24000):
      $phil_health = 287.5;
    break;
    case ($salary >= 24000 && $salary < 25000):
      $phil_health = 300;
    break;
    case ($salary >= 25000 && $salary < 26000):
      $phil_health = 312.5;
    break;
    case ($salary >= 26000 && $salary < 27000):
      $phil_health = 325;
    break;
    case ($salary >= 27000 && $salary < 28000):
      $phil_health = 337.5;
    break;
    case ($salary >= 28000 && $salary < 29000):
      $phil_health = 350;
    break;
    case ($salary >= 29000 && $salary < 30000):
      $phil_health = 362.5;
    break;
    case ($salary >= 30000 && $salary < 31000):
      $phil_health = 375;
    break;
    case ($salary >= 31000 && $salary < 32000):
      $phil_health = 387.5;
    break;
    case ($salary >= 32000 && $salary < 33000):
      $phil_health = 400;
    break;
    case ($salary >= 33000 && $salary < 34000):
      $phil_health = 412.5;
    break;
    case ($salary >= 34000 && $salary < 35000):
      $phil_health = 425;
    break;
    case ($salary >= 35000):
      $phil_health = 437.5;
    break;
    default:
      $phil_health = 0;
    break;
  }

  return $phil_health;
}

/**
 * 5000 = given gross
 * 
 * gross_pay = 5000 (subtract tax-shield, allowances, late/tardiness, absent from this)
 * sss_ee = compute_sss(5000)
 * phil_health = compute_ph(5000)
 * hdmf = 0
 * marital = (db)
 * dependents = (db)
 * 
 * @return
 * income_tax : total deduction to the gross
 * taxable : total deductions in the payslip
 */
static function tax_compute_bir($gross_pay_bir, $sss_ee, $phil_health, $hdmf, $marital_status, $dependents) {
  $taxable = $gross_pay_bir - $sss_ee - $phil_health - $hdmf;
  $excess = 0;
  $income_tax = 0;
  $status = 0;

  if (($marital_status == 'Single' OR $marital_status == 'Married') && $dependents == 0) {
    switch ($taxable) {
      case ($taxable > 1 && $taxable <= 2083):
        $excess = $taxable - 1;
        $status = 1;
        break;
      case ($taxable > 2083 && $taxable <= 2500):
        $excess = $taxable - 2083;
        $status = 2;
        break;
      case ($taxable > 2500 && $taxable <= 3333):
        $excess = $taxable - 2500;
        $status = 3;
        break;
      case ($taxable > 3333 && $taxable <= 5000):
        $excess = $taxable - 3333;
        $status = 4;
        break;
      case ($taxable > 5000 && $taxable <= 7917):
        $excess = $taxable - 5000;
        $status = 5;
        break;
      case ($taxable > 7917 && $taxable <= 12500):
        $excess = $taxable - 7917;
        $status = 6;
        break;
      case ($taxable > 12500 && $taxable <= 22917):
        $excess = $taxable - 12500;
        $status = 7;
        break;
      case ($taxable > 22917):
        $excess = $taxable - 22917;
        $status = 8;
        break;
    }
  }

  if (($marital_status == 'Single' OR $marital_status == 'Married') && $dependents == 1) {
    switch ($taxable) {
      case ($taxable > 1 && $taxable <= 3125):
        $excess = $taxable - 1;
        $status = 1;
        break;
      case ($taxable > 3125 && $taxable <= 3542):
        $excess = $taxable - 3125;
        $status = 2;
        break;
      case ($taxable > 3542 && $taxable <= 4375):
        $excess = $taxable - 3542;
        $status = 3;
        break;
      case ($taxable > 4375 && $taxable <= 6042):
        $excess = $taxable - 4375;
        $status = 4;
        break;
      case ($taxable > 6042 && $taxable <= 8958):
        $excess = $taxable - 6042;
        $status = 5;
        break;
      case ($taxable > 8958 && $taxable <= 13542):
        $excess = $taxable - 8958;
        $status = 6;
        break;
      case ($taxable > 13542 && $taxable <= 23958):
        $excess = $taxable - 13542;
        $status = 7;
        break;
      case ($taxable > 23958):
        $excess = $taxable - 23958;
        $status = 8;
        break;
    }
  }

  if (($marital_status == 'Single' OR $marital_status == 'Married') && $dependents == 2) {
    switch ($taxable) {
      case ($taxable > 1 && $taxable <= 4167):
        $excess = $taxable - 1;
        $status = 1;
        break;
      case ($taxable > 4167 && $taxable <= 4583):
        $excess = $taxable - 4167;
        $status = 2;
        break;
      case ($taxable > 4583 && $taxable <= 5417):
        $excess = $taxable - 4583;
        $status = 3;
        break;
      case ($taxable > 5417 && $taxable <= 7083):
        $excess = $taxable - 5417;
        $status = 4;
        break;
      case ($taxable > 7083 && $taxable <= 10000):
        $excess = $taxable - 7083;
        $status = 5;
        break;
      case ($taxable > 10000 && $taxable <= 14583):
        $excess = $taxable - 10000;
        $status = 6;
        break;
      case ($taxable > 14583 && $taxable <= 25000):
        $excess = $taxable - 14583;
        $status = 7;
        break;
      case ($taxable > 25000):
        $excess = $taxable - 25000;
        $status = 8;
        break;
    }
  }

  if (($marital_status == 'Single' OR $marital_status == 'Married') && $dependents == 3) {
    switch ($taxable) {
      case ($taxable > 1 && $taxable <= 5208):
        $excess = $taxable - 1;
        $status = 1;
        break;
      case ($taxable > 5208 && $taxable <= 5625):
        $excess = $taxable - 5208;
        $status = 2;
        break;
      case ($taxable > 5625 && $taxable <= 6458):
        $excess = $taxable - 5625;
        $status = 3;
        break;
      case ($taxable > 6458 && $taxable <= 8125):
        $excess = $taxable - 6458;
        $status = 4;
        break;
      case ($taxable > 8125 && $taxable <= 11042):
        $excess = $taxable - 8125;
        $status = 5;
        break;
      case ($taxable > 11042 && $taxable <= 15625):
        $excess = $taxable - 11042;
        $status = 6;
        break;
      case ($taxable > 15625 && $taxable <= 26042):
        $excess = $taxable - 15625;
        $status = 7;
        break;
      case ($taxable > 26042):
        $excess = $taxable - 26042;
        $status = 8;
        break;
    }
  }

  if (($marital_status == 'Single' OR $marital_status == 'Married') && $dependents >= 4) {
    switch ($taxable) {
      case ($taxable > 1 && $taxable <= 6250):
        $excess = $taxable - 1;
        $status = 1;
        break;
      case ($taxable > 6250 && $taxable <= 6667):
        $excess = $taxable - 6250;
        $status = 2;
        break;
      case ($taxable > 6667 && $taxable <= 7500):
        $excess = $taxable - 6667;
        $status = 3;
        break;
      case ($taxable > 7500 && $taxable <= 9167):
        $excess = $taxable - 7500;
        $status = 4;
        break;
      case ($taxable > 9167 && $taxable <= 12083):
        $excess = $taxable - 9167;
        $status = 5;
        break;
      case ($taxable > 12083 && $taxable <= 16667):
        $excess = $taxable - 12083;
        $status = 6;
        break;
      case ($taxable > 16667 && $taxable <= 27083):
        $excess = $taxable - 16667;
        $status = 7;
        break;
      case ($taxable > 27083):
        $excess = $taxable - 27083;
        $status = 8;
        break;
    }
  }

  switch ($status) {
    case 1:
      $income_tax = ($excess * 0) + 0;
      break;
    case 2:
      $income_tax = ($excess * .05) + 0;
      break;
    case 3:
      $income_tax = ($excess * .1) + 20.83;
      break;
    case 4:
      $income_tax = ($excess * .15) + 104.17;
      break;
    case 5:
      $income_tax = ($excess * .2) + 354.17;
      break;
    case 6:
      $income_tax = ($excess * .25) + 937.5;
      break;
    case 7:
      $income_tax = ($excess * .3) + 2083.33;
      break;
    case 8:
      $income_tax = ($excess * .32) + 5208.33;
      break;
  }
  
  $income_tax = ($gross_pay_bir >= 3500) ? $income_tax : 0;
  
  $bir_array = array
      (
      'taxable' => $taxable,
      'income_tax' => $income_tax,
  );

  return $bir_array;
}

?>

<?php

function a($raw_data, $precision)
{
ini_set('serialize_precision', $precision);
echo "-- serialize_precision:$precision  --------------------------".PHP_EOL; 
ob_start(); 
var_dump($raw_data);
$var_dump = ob_get_contents();
ob_end_clean();
echo '  var_dump    : '.$var_dump;
echo '  string      : '.$raw_data.PHP_EOL;
echo '  var_export  : '.var_export($raw_data,true) . PHP_EOL;
echo '  serialize   : '.serialize($raw_data) . PHP_EOL;
echo '  json_encode : '.json_encode($raw_data) . PHP_EOL;
echo PHP_EOL;
}

// sample 
$a = 3.1419526535;

// precision 2
$r = round($a, 2);
a($r, -1);
a($r, 100);
a($r, 18);
a($r, 17);
a($r, 16);

// precision 5
$r = round($a, 7);
a($r, -1);
a($r, 100);
a($r, 18);
a($r, 17);
a($r, 16);

/***
 
 result 

*/
/*
-- serialize_precision:-1  --------------------------
  var_dump    : float(3.14)
  string      : 3.14
  var_export  : 3.14
  serialize   : d:3.14;
  json_encode : 3.14

-- serialize_precision:100  --------------------------
  var_dump    : float(3.14)
  string      : 3.14
  var_export  : 3.140000000000000124344978758017532527446746826171875
  serialize   : d:3.140000000000000124344978758017532527446746826171875;
  json_encode : 3.140000000000000124344978758017532527446746826171875

-- serialize_precision:18  --------------------------
  var_dump    : float(3.14)
  string      : 3.14
  var_export  : 3.14000000000000012
  serialize   : d:3.14000000000000012;
  json_encode : 3.14000000000000012

-- serialize_precision:17  --------------------------
  var_dump    : float(3.14)
  string      : 3.14
  var_export  : 3.1400000000000001
  serialize   : d:3.1400000000000001;
  json_encode : 3.1400000000000001

-- serialize_precision:16  --------------------------
  var_dump    : float(3.14)
  string      : 3.14
  var_export  : 3.14
  serialize   : d:3.14;
  json_encode : 3.14

-- serialize_precision:-1  --------------------------
  var_dump    : float(3.1419527)
  string      : 3.1419527
  var_export  : 3.1419527
  serialize   : d:3.1419527;
  json_encode : 3.1419527

-- serialize_precision:100  --------------------------
  var_dump    : float(3.1419527)
  string      : 3.1419527
  var_export  : 3.14195270000000004273488229955546557903289794921875
  serialize   : d:3.14195270000000004273488229955546557903289794921875;
  json_encode : 3.14195270000000004273488229955546557903289794921875

-- serialize_precision:18  --------------------------
  var_dump    : float(3.1419527)
  string      : 3.1419527
  var_export  : 3.14195270000000004
  serialize   : d:3.14195270000000004;
  json_encode : 3.14195270000000004

-- serialize_precision:17  --------------------------
  var_dump    : float(3.1419527)
  string      : 3.1419527
  var_export  : 3.1419527
  serialize   : d:3.1419527;
  json_encode : 3.1419527

-- serialize_precision:16  --------------------------
  var_dump    : float(3.1419527)
  string      : 3.1419527
  var_export  : 3.1419527
  serialize   : d:3.1419527;
  json_encode : 3.1419527

*/



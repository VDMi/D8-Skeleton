<?php

$string = file_get_contents("sites_config.json");
$json = json_decode($string, TRUE);

if (strcmp($argv[1], "sites_count") == 0) {
  $result = count($json['sites']);
  print_r($result);
}
else {
  if (strcmp($argv[1], "machine_name") == 0) {
    $keys = array_keys($json['sites']);
    print_r($keys[$argv[2]]);
  }
  else {
    foreach ($json as $values) {
      $values = array_values($json['sites']);
      if (is_array($values[$argv[1]][$argv[2]])) {
        print_r(implode(',', $values[$argv[1]][$argv[2]]));
      }
      else {
        print_r($values[$argv[1]][$argv[2]]);
      }
    }
  }
}

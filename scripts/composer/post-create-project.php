#!/usr/bin/php
<?php
$human_name = '';
while(empty(trim($human_name))) {
  $human_name = readline('Human Name: ');
}
$human_name = trim($human_name);

$machine_readable = strtolower($human_name);
$machine_readable = preg_replace('@[^a-z0-9_]+@','_',$machine_readable);

$machine_name = readline('Machine Name (' . $machine_readable . '): ');
if (empty(trim($machine_name))) {
  $machine_name = $machine_readable;
}

print PHP_EOL;
print '-----' . PHP_EOL;
print 'Human name: ' . $human_name . PHP_EOL;
print 'Machine Name: ' . $machine_name . PHP_EOL;
print '-----' . PHP_EOL;
print PHP_EOL;

$confirm = '';
while(trim($confirm) != "Y") {
  $confirm = readline('Type Y to continue: ');
}

$base_path = './';

foreach ($iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($base_path, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST) as $item) {
  $contains_machine_name = strpos($iterator->getFilename(), '__PROJECT_MACHINE_NAME__');

  if ($contains_machine_name !== FALSE) {
    $new_name = str_replace('__PROJECT_MACHINE_NAME__', $machine_name, $base_path . $iterator->getSubPathName());
    //rename($base_path . $iterator->getSubPathName(), $new_name);
    if ($item->isDir()) {
      print 'Renamed Dir: ' . $base_path . $iterator->getSubPathName() . ' => ' . $new_name . PHP_EOL;
    } else {
      print 'Renamed File: ' . $base_path . $iterator->getSubPathName() . ' => ' . $new_name . PHP_EOL;
    }
  }
}

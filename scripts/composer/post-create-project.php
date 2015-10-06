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

$base_path = './web/';

$replacements = array(
  '__PROJECT_MACHINE_NAME__' => $machine_name,
  '__PROJECT_HUMAN_NAME__' => $human_name,
  '__RANDOM_HASH_SALT__' => generateRandomString(32),
);

$renames = array();

foreach ($iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($base_path, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST) as $item) {
  if (!$item->isDir()) {
    $old_contents = file_get_contents($base_path . $iterator->getSubPathName());
    $new_contents = str_replace(array_keys($replacements), array_values($replacements), $old_contents);
    if ($new_contents != $old_contents) {
      file_put_contents($base_path . $iterator->getSubPathName(), $new_contents);
      print 'Updated File: ' . $base_path . $iterator->getSubPathName() . PHP_EOL;
    }
  }

  $contains_machine_name = strpos($iterator->getFilename(), '__PROJECT_MACHINE_NAME__');

  if ($contains_machine_name !== FALSE) {
    $new_name = str_replace('__PROJECT_MACHINE_NAME__', $machine_name, $base_path . $iterator->getSubPathName());
    $renames[] = array('source' => $base_path . $iterator->getSubPathName(), 'target' => $new_name);
    if ($item->isDir()) {
      print 'Renamed Dir: ' . $base_path . $iterator->getSubPathName() . ' => ' . $new_name . PHP_EOL;
    } else {
      print 'Renamed File: ' . $base_path . $iterator->getSubPathName() . ' => ' . $new_name . PHP_EOL;
    }
  }
}

foreach ($renames as $rename) {
  rename($rename['source'], $rename['target']);
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

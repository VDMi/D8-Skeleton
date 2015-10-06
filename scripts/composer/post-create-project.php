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

$base_path = './web';

$replacements = array(
  '__PROJECT_MACHINE_NAME__' => $machine_name,
  '__PROJECT_HUMAN_NAME__' => $human_name,
  '__RANDOM_HASH_SALT__' => generateRandomString(64),
);

function updateDir($main, $replacements, $machine_name){
  $dirHandle = opendir($main);
  while($file = readdir($dirHandle)) {
    $curpath = $main . '/' . $file;

    $contains_machine_name = strpos($main . '/' . $file, '__PROJECT_MACHINE_NAME__');
    if ($contains_machine_name !== FALSE) {
      $new_path = str_replace('__PROJECT_MACHINE_NAME__', $machine_name, $curpath);
      rename($curpath, $new_path);
      print 'Renamed ' . $curpath . ' to ' . $new_path . PHP_EOL;
      $curpath = $new_path;
    }

    if(is_dir($curpath) && $file != '.' && $file != '..'){
      updateDir($curpath, $replacements, $machine_name);
    }
    else{
      $old_contents = file_get_contents($curpath);
      $new_contents = str_replace(array_keys($replacements), array_values($replacements), $old_contents);
      if ($new_contents != $old_contents) {
        file_put_contents($curpath, $new_contents);
        print 'Updated File: ' . $curpath . PHP_EOL;
      }
    }
  }
}

updateDir($base_path, $replacements, $machine_name);

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

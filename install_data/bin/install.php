<?php
#
# The script gets full path of current script, creates log/lib dirs
# in the path. Prepares app.config and *.bat files from templates.
#
function filter_file($fname_in, $fname_out, $replace) {
  #print "filter_file:\n$fname_in\n$fname_out\n$replace\n";
  if(!file_exists($fname_in)){
    print "no input file\n";
	return;
  }
  $fdi = fopen($fname_in, "r");
  if($fdi){
    $fdo = fopen($fname_out, "w");
    if($fdo){
      while(!feof($fdi)){
        $str = fgets($fdi, 4096);
        $res = preg_replace ("/%ROOT%/", $replace, $str);
        fputs($fdo, $res);
      }
      fclose($fdo);
    }
    fclose($fdi);
  }
  #print "filter_file end\n";
}

$app = "erpher";

$cur1 = realpath($argv[0]);
$d0 = dirname($cur1);
$d1 = dirname($d0);
$d1b = preg_replace('/\\\\/', '/', $d1); # replace \ with / for windows

$files_all = array(
  "/etc/app.config"
);
$files_w32 = array(
  "/bin/start.bat",
  "/bin/start_console.bat",
  "/bin/stop.bat"
);
$config = $d1b . "/etc/app.config";
$tmpl = $d1b . "/etc/app.config.tmpl";
$lib = $d1b . "/var/lib/" . $app;
$log = $d1b . "/var/log/" . $app;

mkdir($lib, 0750, true);
mkdir($log, 0750, true);

foreach($files_all as $item){
  print "main all, item=$item\n";
  $t_out_name = $d1b . $item;
  $t_in_name = $t_out_name . ".tmpl";
  filter_file($t_in_name, $t_out_name, $d1b);
}

if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
  foreach($files_w32 as $item){
    print "main win, item=$item\n";
    $t_out_name = $d1b . $item;
    $t_in_name = $t_out_name . ".tmpl";
    filter_file($t_in_name, $t_out_name, $d1b);
  }
}
?>

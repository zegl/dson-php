<?php

if (extension_loaded('xhprof')) {
    include_once '/usr/share/php/xhprof_lib/utils/xhprof_lib.php';
    include_once '/usr/share/php/xhprof_lib/utils/xhprof_runs.php';
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
}

require_once '../DSON.php';

use \zegl\dson\DSON;

$tests = array(
    array('poop' => json_decode('"\ud83d\udca9"')),
    'Hej, jag är en text med fina krumilurer'
);

foreach ($tests as $v)
{
    var_dump($v);
    $encode = DSON::encode($v);
    $decode = DSON::decode($encode, true);
    var_dump($decode);
}

if (extension_loaded('xhprof')) {
    $xhprof_data = xhprof_disable();
    $xhprof_runs = new XHProfRuns_Default('/storage/xhprof/');
    $a = $xhprof_runs->save_run($xhprof_data, 'dson');
    var_dump($a);
}

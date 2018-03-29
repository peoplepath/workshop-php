<?php
require __DIR__ . '/lib.php';

// NOTICE ?? operator, it's equivalent for
// $filename = isset($argv[1]) ? $argv[1] : 'php://stdin';
// and it works even for nested arrays, for example:
//
// old
// isset($array[1], $array[1][2], $array[1][2][3]) ? $array[1][2][3] : null;
//
// new
// $array[1][2][3] ?? null;
//

$pipeline = new Pipeline;
$pipeline->addPipe('trim', 'strtolower');
$pipeline->addPipe(build_extract('/\.([^\.]+)$/'));
$pipeline->addPipe(new Stats);
$pipeline->addPipe(build_sort('arsort'));

$nicePrint = new NicePrint(10);

$filename = $argv[1] ?? 'php://stdin';
$lines = read_file($filename);

foreach ($pipeline->input($lines) as $array) {
    $nicePrint->show($array);
}

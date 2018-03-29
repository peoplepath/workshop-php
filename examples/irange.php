<?php
// implementation of classic range() function as Iterator by Generator
function iterable_range(int $start, int $end, int $step=1): iterable {
  // NOTICE that this line is called after function (in foreach)
  echo 'line: ' . __LINE__ . PHP_EOL;

  for ($i = $start; $i <= $end; $i += $step) {
    yield $i;
  }

  echo 'line: ' . __LINE__ . PHP_EOL;

  return 'a result';
}

$range = iterable_range(1, 10, 2);

echo 'line: ' . __LINE__ . PHP_EOL;

// this will throw an exception, try it
// var_dump($range->getReturn());

foreach ($range as $i) {
    echo $i . PHP_EOL;
}

var_dump($range->getReturn());

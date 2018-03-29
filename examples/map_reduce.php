<?php
// example of functional capabilities of PHP

// see https://en.wikipedia.org/wiki/MapReduce
function map_reduce(array $values, callable $map, callable $reduce) {
  $mapped = [];

  // this function will be passed into MAP function which can emit any amount
  // of key/value pairs, in here we're saving just to array in practice there'll
  // be a database, distribute storage, etc.
  //
  // NOTICE variable $mapped is passed in like a reference
  $emit = function ($key, $value) use (&$mapped) {
    $mapped[$key][] = $value;
  };

  // iterate over input values and apply MAP function
  foreach ($values as $value) {
    $map($value, $emit);
  }

  // this function will emit result of reduce in practice will pass partial
  // reduced value to another cycle or save result to some storage
  $emit = function ($key, $value) {
    echo "$key: $value" . PHP_EOL;
  };

  // reduce mapped values, in practice will be distributed but API will be the same
  foreach ($mapped as $key => $values) {
    $reduce($key, $values, $emit);
  }
}

// map value to odd/even
$map = function ($value, callable $emit) {
  $value % 2 ? $emit('liche', $value) : $emit('sude', $value);
};

// a builder of reduce function, you can pass any function which process array
// of values
//
// NOTICE that we're returning function that's the one of requirements of
// functional language
//
// see https://en.wikipedia.org/wiki/Functional_programming#First-class_and_higher-order_functions
$reduceWith = function(callable $callback): Closure {
  return function ($key, $values, $emit) use ($callback) {
    $emit($key, $callback($values));
  };
};

map_reduce(range(1,6), $map, $reduceWith('array_sum'));

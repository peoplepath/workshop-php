<?php
// uncomment following and observe that exception will be thrown on the first line
// declare(strict_types=1);

function to_int($str): int {
    return $str;
}

var_dump(to_int('123')); // return int(123)
var_dump(to_int('abc')); // throw an exception

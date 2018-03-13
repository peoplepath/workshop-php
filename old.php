<?php
$pattern = '/test\.(\w+)/';

// read and parse file
$log  = file_get_contents($argv[1]);
$rows = explode(PHP_EOL, $log);

// build stats
$stats = array();
foreach ($rows as $row) {
    // decorator: extract log level
    if (preg_match($pattern, $row, $matches)) {
        $level = strtolower($matches[1]);

        // filter: do not accept DEBUG
        if ($level != 'debug') {
            if (array_key_exists($level, $stats)) {
                $stats[$level]++;
            } else {
                $stats[$level] = 1;
            }
        }
    }
}

// show stats
arsort($stats);
foreach ($stats as $level => $count) {
    echo "$level: $count" . PHP_EOL;
}

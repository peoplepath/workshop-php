<?php

// implementing classes apply a filter to a string
interface Filter {
    function filter(string $str): string;
}

abstract class CascadeFilter implements Filter {
    private $_previousFilter = null;

    function __construct(?Filter $filter) {
        $this->_previousFilter = $filter;
    }

    function filter(string $str): string {
        if ($this->_previousFilter) {
            return $this->_previousFilter->filter($str);
        } else {
            return $str;
        }
    }
}

class IsNotDebug extends CascadeFilter {
    function filter(string $str): string {
        $str = parent::filter($str);
        return $str == 'debug' ? '' : $str;
    }
}

class ExtractLogLevel extends CascadeFilter {
    const PATTERN = '/test\.(\w+)/';

    function filter(string $str): string {
        $str = parent::filter($str);

        if (preg_match(self::PATTERN, $str, $matches)) {
            return strtolower($matches[1]);
        } else {
            return '';
        }
    }
}


class FileRowReader extends FilterIterator {
    private $_filter = null;

    function __construct(string $filename, Filter $filter) {
        $this->_filter = $filter;

        // parent is a generator
        parent::__construct(
            (function($filename){
                $f = fopen($filename, 'r');
                try {
                    while ($row = fgets($f)) {
                        yield $row;
                    }
                } finally {
                    fclose($f);
                }
            })($filename)
        );
    }

    public function current() {
        return $this->_filter->filter($this->getInnerIterator()->current());
    }

    public function accept() {
        if ($this->current() == '') {
            return false;
        }
        return true;
    }
}

// read and parse file
$filter = new IsNotDebug(new ExtractLogLevel(null));
$fileReader = new FileRowReader($argv[1], $filter);

// build stats
$stats = new class($fileReader) extends ArrayIterator {
    function __construct(Iterable $iterator) {
        $stats = array();
        foreach ($iterator as $level) {
            if (array_key_exists($level, $stats)) {
                $stats[$level]++;
            } else {
                $stats[$level] = 1;
            }
        }
        arsort($stats);
        parent::__construct($stats);
    }
};

// show stats
foreach ($stats as $level => $count) {
    echo "$level: $count" . PHP_EOL;
}

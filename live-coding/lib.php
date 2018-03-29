<?php
// reads given file line by line
function read_file(string $filename): iterable {
    $file = fopen($filename, 'r');

    try {
        while ($line = fgets($file)) {
            yield $line;
        }
    } finally {
        fclose($file);
    }
}

// returns a function which returns a match of given pattern
// see http://php.net/manual/en/book.pcre.php
function build_extract(string $pattern) {
    return function (string $line) use ($pattern) {
        if (preg_match($pattern, $line, $matches)) {
            return $matches[1] ?? null;
        }
    };
}

// callable object which counts number of given domains are return array with
// that statistic
class Stats {
    private $stats = [];

    public function __invoke(string $domain) {
        $this->stats[$domain] = ($this->stats[$domain] ?? 0) + 1;

        return $this->stats;
    }
}

// creates immutable callable sort function
function build_sort(callable $sort): callable {
    return function (array $array) use ($sort): array {
        $sort($array);
        return $array;
    };
}

// this object represents a pipeline of callbacks (pipes) which are called in
// sequence and output of a callback is given as an input of another callback
class Pipeline {
    private $pipes = [];

    // add pipe(s) to pipeline
    public function addPipe(callable ...$pipes) {
        $this->pipes = array_merge($this->pipes, $pipes);
    }

    // iterate over input, pass it through a pipes and yield a result if not empty
    public function input(iterable $items): iterable {
        foreach ($items as $item) {
            foreach ($this->pipes as $pipe) {
                if (empty($item = $pipe($item))) {
                    break;
                }
            }

            // yield only non-empty values
            if ($item) yield $item;
        }
    }
}

class NicePrint {
    private $noPrintedLines = 0;
    private $lineLimit;

    public function __construct(int $lineLimit=10) {
        $this->lineLimit = $lineLimit;
    }

    public function show(array $array) {
        // move cursor back to the start
        echo str_repeat("\033[F", $this->noPrintedLines);

        // find longest domain name
        $longestDomain = - max(6, ...array_map('strlen', array_keys($array)));
        $sumLength     = strlen(array_sum($array));

        // print  limited number of lines
        $limit = $this->lineLimit;
        $lines = 0;
        foreach ($array as $key => $value) {
            if ($limit-- > 0) {
               printf("%{$sumLength}s : %{$longestDomain}s" . PHP_EOL, $value, $key);
            $lines++;
            }
        }

        // if some domains left print them as others
        if ($array) {
            printf("%{$sumLength}s : %{$longestDomain}s" . PHP_EOL, array_sum($array), 'others');
            $lines++;
        }

        $this->noPrintedLines = $lines;
    }
}

<?php
declare(strict_types=1);

namespace ZdenekZahor\IntraWorldsFileProcessor;

use Generator;

class FileReader
{

    /**
     * [see] http://php.net/manual/en/language.generators.overview.php#112985
     *
     * @param string $file
     * @return Generator
     */
    public function readFile(string $file): Generator
    {
        $f = fopen($file, 'r');
        try {
            while ($line = fgets($f)) {
                yield $line;
            }
        } finally {
            fclose($f);
        }
    }
}

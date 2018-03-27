<?php
declare(strict_types=1);

namespace ZdenekZahor\IntraWorldsFileProcessor;

interface IFilter
{

    /**
     * @param string $row
     * @return bool
     */
    public function filterRow(string $row): bool;
}

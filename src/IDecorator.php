<?php
declare(strict_types=1);

namespace ZdenekZahor\IntraWorldsFileProcessor;

interface IDecorator
{

    /**
     * @param string $row
     * @return string
     */
    public function decorateRow(string $row): string;
}

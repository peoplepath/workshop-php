<?php
declare(strict_types=1);

namespace ZdenekZahor\IntraWorldsFileProcessor;

class Result
{

    /**
     * @var ResultItem[]
     */
    private $items;

    public function __construct()
    {
        $this->items = [];
    }

    /**
     * @return ResultItem[]
     */
    public function getItems(): array
    {
        return array_values($this->items);
    }

    /**
     * @param string $name
     */
    public function incrementCount(string $name): void
    {
        if (isset($this->items[$name])) {
            $this->items[$name]->incrementCount();
        } else {
            $this->items[$name] = new ResultItem($name, 1);
        }
    }
}

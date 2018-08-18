<?php
declare(strict_types=1);

namespace ZdenekZahor\IntraWorldsFileProcessor;

class DataProcessor
{

    /**
     * @var IDecorator
     */
    private $decorator;

    /**
     * @var IFilter
     */
    private $filter;

    /**
     * @param IDecorator $decorator
     * @param IFilter $filter
     */
    public function __construct(IDecorator $decorator, IFilter $filter)
    {
        $this->decorator = $decorator;
        $this->filter = $filter;
    }

    /**
     * @param iterable $rows
     * @return Result
     */
    public function process(iterable $rows): Result
    {
        $result = new Result();

        foreach ($rows as $row) {
            $decoratedRow = $this->decorator->decorateRow($row);
            if ($this->filter->filterRow($decoratedRow)) {
                $result->incrementCount($decoratedRow);
            }
        }

        return $result;
    }
}

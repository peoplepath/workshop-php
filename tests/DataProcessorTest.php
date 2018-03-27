<?php
declare(strict_types=1);

namespace ZdenekZahor\IntraWorldsFileProcessor;

use PHPUnit\Framework\TestCase;

class DataProcessorTest extends TestCase
{
    public function testProcess(): void
    {
        $processor = new DataProcessor(
            new class() implements IDecorator
            {
                public function decorateRow(string $row): string
                {
                    if (preg_match('/test\.(\w+)/', $row, $matches)) {
                        return strtolower($matches[1]);
                    }
                    return '';
                }
            },
            new class() implements IFilter
            {
                public function filterRow(string $row): bool
                {
                    return $row !== '' && $row !== 'debug';
                }
            }
        );

        $inputRows = explode(PHP_EOL, file_get_contents(__DIR__ . '/../example.log'));
        $resultItems = $processor->process($inputRows)->getItems();

        $this->assertCount(6, $resultItems);
        $this->assertEquals('error', $resultItems[0]->getName());
        $this->assertEquals(2, $resultItems[0]->getCount());
        $this->assertEquals('warning', $resultItems[1]->getName());
        $this->assertEquals(2, $resultItems[1]->getCount());
        $this->assertEquals('info', $resultItems[2]->getName());
        $this->assertEquals(1, $resultItems[2]->getCount());
        $this->assertEquals('notice', $resultItems[3]->getName());
        $this->assertEquals(2, $resultItems[3]->getCount());
        $this->assertEquals('emergency', $resultItems[4]->getName());
        $this->assertEquals(1, $resultItems[4]->getCount());
        $this->assertEquals('alert', $resultItems[5]->getName());
        $this->assertEquals(1, $resultItems[5]->getCount());
    }
}

<?php
declare(strict_types=1);

namespace ZdenekZahor\IntraWorldsFileProcessor\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ZdenekZahor\IntraWorldsFileProcessor\DataProcessor;
use ZdenekZahor\IntraWorldsFileProcessor\FileReader;
use ZdenekZahor\IntraWorldsFileProcessor\IDecorator;
use ZdenekZahor\IntraWorldsFileProcessor\IFilter;

class ProcessFileCommand extends Command
{
    private const NAME = 'process-file';

    private const ARG_FILE = 'file';

    /**
     * @var FileReader
     */
    private $fileReader;

    /**
     * @var DataProcessor
     */
    private $dataProcessor;

    public function __construct()
    {
        parent::__construct();

        $this->fileReader = new FileReader();

        $this->dataProcessor = new DataProcessor(
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
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName(self::NAME);
        $this->addArgument(self::ARG_FILE, InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        // TODO Validace vstupního souboru.
        $result = $this->dataProcessor->process($this->fileReader->readFile(__DIR__ . '/../../../' . $input->getArgument(self::ARG_FILE)));

        foreach ($result->getItems() as $resultItem) {
            $output->writeln("{$resultItem->getName()}: {$resultItem->getCount()}");
        }

        return 0;
    }
}

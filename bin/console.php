#!/usr/bin/env php
<?php
declare(strict_types=1);

use Symfony\Component\Console\Application;
use ZdenekZahor\IntraWorldsFileProcessor\Cli\Command\ProcessFileCommand;

require_once __DIR__ . '/../vendor/autoload.php';

$application = new Application();

$application->add(new ProcessFileCommand());

$application->run();

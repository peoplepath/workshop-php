<?php

namespace Reader;

require_once __DIR__ . "/Processor.php";
require_once __DIR__ . "/FileIterator.php";
require_once __DIR__ . "/IPresenter.php";
require_once __DIR__ . "/Presenter/SimplePrinter.php";
require_once __DIR__ . "/Presenter/WordsCounter.php";
require_once __DIR__ . "/Filter.php";
require_once __DIR__ . "/Filter/SemicolonSplitter.php";
require_once __DIR__ . "/Filter/MinLengthJoiner.php";
require_once __DIR__ . "/Filter/StopwordsOmitter.php";


// Filter
echo "\n\nFilter ---------------------------------------\n";
$inputStream = new Filter\MinLengthJoiner(new Filter\SemicolonSplitter(new \ArrayIterator(['abc;def', 'ghi;jklmn;o', 'p;qrstuvw;xyz'])));


foreach ($inputStream as $key => $value) {
	echo "$key: $value\n";
}


// Presenter
echo "\n\nPresenter ---------------------------------------\n";
$inputLines = [
	'Hello world! This is words counter presenter speaking!',
	'Words counter is the built in presenter of Reader package',
	'This is the best Reader in the world!',
];

$presenter = new Presenter\WordsCounter(['world', 'Reader', 'is', 'in']);

foreach ($inputLines as $inputLine) {
	echo $presenter->apply($inputLine) . "\n";
}


// Processor
echo "\n\nProcessor ---------------------------------------\n";
$inputStream = [
	'foo',
	'bar',
	'baz',
];

$p = new Processor(new \ArrayIterator($inputStream), new Presenter\SimplePrinter());

$output = $p->output(TRUE);
foreach ($output as $line) {
	echo $line . "\n";
}


//File iterator
echo "\n\nFileIterator ---------------------------------------\n";
$inputStream = new FileIterator(__DIR__ . '/data.txt');

foreach ($inputStream as $key => $value) {
	echo "$key: $value\n";
}

//Putting it all together: https://github.com/intraworlds/workshop-php
echo "\n\nHeureka ---------------------------------------\n";

$inputStream = new FileIterator(__DIR__ . '/data.txt');
$filteredStream = new Filter\StopwordsOmitter($inputStream, 'test.DEBUG');
$countsPresenter = new Presenter\WordsCounter(['ERROR', 'WARNING', 'INFO', 'NOTICE', 'EMERGENCY', 'ALERT']);

$p = new Processor($filteredStream, $countsPresenter);

foreach ($p->output(FALSE) as $outputLine) {
	echo $outputLine . "\n";
}




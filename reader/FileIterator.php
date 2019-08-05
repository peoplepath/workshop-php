<?php

namespace Reader;


final class FileIterator implements \Iterator
{

	/** @var string */
	private $filePath;

	/** File handle */
	private $f;

	/** @var string */
	private $current;

	/** @var int */
	private $key = -1;


	public function __construct(string $filePath)
	{
		$this->filePath = $filePath;
	}


	public function __destruct()
	{
		if ($this->f) {
			fclose($this->f);
		}
	}


	public function current()
	{
		return $this->current;
	}


	public function key()
	{
		return $this->key;
	}


	public function next()
	{
		$this->current = trim(fgets($this->f), PHP_EOL);
		$this->key++;
	}


	public function rewind()
	{
		$this->openFile();
	}


	public function valid(): bool
	{
		return !feof($this->getFileHandle());
	}


	private function getFileHandle()
	{
		return $this->f ?? $this->openFile();
	}


	private function openFile()
	{
		if ($this->f) {
			fclose($this->f);
		}
		$this->f = fopen($this->filePath, 'r');
		$this->key = -1;
		$this->next();
		return $this->f;
	}
}

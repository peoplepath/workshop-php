<?php

namespace Reader;


abstract class Filter implements \Iterator
{

	/** @var \Iterator */
	protected $delegate;

	/** @var int */
	private $key = 0;

	/** @var array */
	private $buffer = [];


	public function __construct(\Iterator $delegate)
	{
		$this->delegate = $delegate;
	}


	abstract function bufferCurrent(): array;


	public function current()
	{
		if (empty($this->buffer)) {
			$this->buffer = $this->bufferCurrent();
		}
		return $this->buffer[0];
	}


	public function key()
	{
		return $this->key;
	}


	public function next()
	{
		array_shift($this->buffer);
		$this->key++;
		if (empty($this->buffer)) {
			$this->delegate->next();
		}
	}


	public function rewind()
	{
		$this->key = 0;
		$this->buffer = [];
		$this->delegate->rewind();
	}


	public function valid(): bool
	{
		if (empty($this->buffer)) {
			return $this->delegate->valid();
		} else {
			return TRUE;
		}
	}
}

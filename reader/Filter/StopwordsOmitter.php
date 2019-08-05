<?php

namespace Reader\Filter;


final class StopwordsOmitter extends \Reader\Filter
{

	/** @var string */
	private $stopword;


	public function __construct(\Iterator $delegate, string $stopword)
	{
		parent::__construct($delegate);
		$this->stopword = $stopword;
	}


	public function bufferCurrent(): array
	{
		return [$this->delegate->current()];
	}


	public function valid(): bool
	{
		if ($this->delegate->valid()) {
			if (\strpos($this->delegate->current(), $this->stopword) !== FALSE) {
				$this->delegate->next();
				return $this->valid();
			}
			return TRUE;
		}
		return FALSE;
	}
}

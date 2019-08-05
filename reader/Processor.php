<?php

namespace Reader;


final class Processor
{

	/** @var \Iterator */
	private $inputStream;

	/** @var Presenter */
	private $presenter;


	public function __construct(\Iterator $inputStream, IPresenter $presenter)
	{
		$this->inputStream = $inputStream;
		$this->presenter = $presenter;
	}


	public function output($rolling = FALSE)
	{
		foreach ($this->inputStream as $inputLine) {
			$output = $this->presenter->apply($inputLine);
			if ($rolling) {
				yield $output;
			}
		}
		if (!$rolling) {
			yield $output;
		}
	}
}

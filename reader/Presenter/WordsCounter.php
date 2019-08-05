<?php

namespace Reader\Presenter;


final class WordsCounter implements \Reader\IPresenter
{

	/** @var array */
	private $words = [];

	/** @var counts */
	private $counts = [];


	public function __construct(array $words)
	{
		$this->words = $words;
	}


	public function apply(string $inputLine): string
	{
		foreach ($this->words as $word) {
			if (strpos($inputLine, $word) !== FALSE) {
				$this->counts[$word] = ($this->counts[$word] ?? 0) + 1;
			}
		}

		return $this->output();
	}


	private function output(): string
	{
		$output = "----------------------------\n";
		foreach ($this->counts as $word => $count) {
			$output .= "$word\t$count\n";
		}
		return $output;
	}
}

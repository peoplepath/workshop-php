<?php

namespace Reader\Filter;


final class MinLengthJoiner extends \Reader\Filter
{

	const MIN_LENGTH = 5;


	public function bufferCurrent(): array
	{
		$current = $this->delegate->current();

		while (strlen($current) < static::MIN_LENGTH && $this->delegate->valid()) {
			$this->delegate->next();
			if ($this->delegate->valid()) {
				$current .= '|' . $this->delegate->current();
			}
		}

		return [$current];
	}
}

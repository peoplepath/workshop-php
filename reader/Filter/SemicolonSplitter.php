<?php

namespace Reader\Filter;


final class SemicolonSplitter extends \Reader\Filter
{


	public function bufferCurrent(): array
	{
		$current = $this->delegate->current();
		return explode(';', $current);
	}
}

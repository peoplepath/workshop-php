<?php

namespace Reader\Presenter;


final class SimplePrinter implements \Reader\IPresenter
{


	public function apply(string $inputLine): string
	{
		return $inputLine;
	}
}

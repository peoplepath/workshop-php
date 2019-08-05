<?php

namespace Reader;


interface IPresenter
{


	public function apply(string $inputLine): string;
}

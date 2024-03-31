<?php

namespace Siyabdev\EditorjsParserPhp;

interface ShouldCheckForPropertyExistence
{
	public function checkPropertyExistence(array $block): void;
}

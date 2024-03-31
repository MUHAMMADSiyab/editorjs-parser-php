<?php

namespace Siyabdev\EditorjsParserPhp\Exceptions;

use Exception;
use Throwable;

class PropertyNotFoundException extends Exception
{
	public function __construct(
		$section = "",
		$property_name = "",
		$code = 0,
		Throwable $previous = null
	) {
		parent::__construct(
			sprintf("%s Section: Required property `%s` not found", ucfirst($section), $property_name),
			$code,
			$previous
		);
	}
}

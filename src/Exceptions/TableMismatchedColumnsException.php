<?php

namespace Siyabdev\EditorjsParserPhp\Exceptions;

use Exception;
use Throwable;

class TableMismatchedColumnsException extends Exception
{
	public function __construct($message = "", $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}

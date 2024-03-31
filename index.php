<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require "vendor/autoload.php";

use Siyabdev\EditorjsParserPhp\Parser;

$content = file_get_contents('content.json');

$parser = new Parser();

$parsed = $parser
	->only('header', 'list')
	->parse($content);

echo $parsed;

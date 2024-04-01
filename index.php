<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require "vendor/autoload.php";

use MuhammadSiyab\EditorjsParserPhp\Parser;

$content = file_get_contents('content.json');

$parser = new Parser();

$parsed = $parser
	->only('header', 'list', 'code')
	->parse($content);

echo $parsed;

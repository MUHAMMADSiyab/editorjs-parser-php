<?php

namespace MuhammadSiyab\EditorjsParserPhp;

use MuhammadSiyab\EditorjsParserPhp\Exceptions\PropertyNotFoundException;

class ParagraphParser implements ShouldCheckForPropertyExistence
{
	/**	 
	 * @param array $paragraph_block
	 * @throws PropertyNotFoundException
	 * @return void
	 */
	public function checkPropertyExistence(array $paragraph_block): void
	{
		if (!array_key_exists('data', $paragraph_block)) {
			throw new PropertyNotFoundException('paragraph', 'data');
		}

		if (!array_key_exists('text', $paragraph_block['data'])) {
			throw new PropertyNotFoundException('paragraph', 'text');
		}
	}

	/**
	 * @param array $paragraph_block
	 * @return string
	 */
	public static function parse(array $paragraph_block): string
	{
		$parser = new ParagraphParser;
		$parser->checkPropertyExistence($paragraph_block);

		$data = $paragraph_block['data']['text'];

		return "<p>{$data}</p>";
	}
}

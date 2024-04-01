<?php

namespace MuhammadSiyab\EditorjsParserPhp;

use MuhammadSiyab\EditorjsParserPhp\Exceptions\PropertyNotFoundException;

class HeadingParser implements ShouldCheckForPropertyExistence
{
	/**
	 * @param array $heading_block
	 * @throws PropertyNotFoundException
	 * @return void
	 */
	public function checkPropertyExistence(array $heading_block): void
	{
		if (!array_key_exists('data', $heading_block)) {
			throw new PropertyNotFoundException('heading', 'data');
		}

		if (!array_key_exists('text', $heading_block['data'])) {
			throw new PropertyNotFoundException('heading', 'text');
		}

		if (!array_key_exists('level', $heading_block['data'])) {
			throw new PropertyNotFoundException('heading', 'level');
		}
	}

	/**
	 * @param array $heading_block
	 * @return string
	 */
	public static function parse(array $heading_block): string
	{
		$parser = new HeadingParser;
		$parser->checkPropertyExistence($heading_block);

		$data = $heading_block['data']['text'];
		$level = $heading_block['data']['level'];

		return "<h{$level}>{$data}</h{$level}>";
	}
}

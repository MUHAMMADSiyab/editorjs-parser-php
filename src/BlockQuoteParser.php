<?php

namespace MuhammadSiyab\EditorjsParserPhp;

use MuhammadSiyab\EditorjsParserPhp\Exceptions\PropertyNotFoundException;

class BlockQuoteParser implements ShouldCheckForPropertyExistence
{
	public function checkPropertyExistence(array $blockquote_block): void
	{
		if (!array_key_exists('data', $blockquote_block)) {
			throw new PropertyNotFoundException('blockquote', 'data');
		}

		if (!array_key_exists('text', $blockquote_block['data'])) {
			throw new PropertyNotFoundException('blockquote', 'text');
		}

		if (!array_key_exists('caption', $blockquote_block['data'])) {
			throw new PropertyNotFoundException('blockquote', 'caption');
		}

		if (!array_key_exists('alignment', $blockquote_block['data'])) {
			throw new PropertyNotFoundException('blockquote', 'alignment');
		}
	}

	/**
	 * @param array $blockquote_block
	 * @return string
	 */
	public static function parse(array $blockquote_block): string
	{
		$parser = new BlockQuoteParser;
		$parser->checkPropertyExistence($blockquote_block);

		$text = $blockquote_block['data']['text'];
		$caption = $blockquote_block['data']['caption'];
		$alignment = $blockquote_block['data']['alignment'];

		return "<blockquote style='text-align: {$alignment};'>
			<p><em><q>{$text}</q></em></p>
			<footer>- {$caption}</footer>
		</blockquote>";
	}
}

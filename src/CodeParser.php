<?php

namespace Siyabdev\EditorjsParserPhp;

use Siyabdev\EditorjsParserPhp\Exceptions\PropertyNotFoundException;

class CodeParser implements ShouldCheckForPropertyExistence
{
	/**
	 * @param array $code_block
	 * @throws PropertyNotFoundException
	 * @return void
	 */
	public function checkPropertyExistence(array $code_block): void
	{
		if (!array_key_exists('data', $code_block)) {
			throw new PropertyNotFoundException('code', 'data');
		}

		if (!array_key_exists('code', $code_block['data'])) {
			throw new PropertyNotFoundException('code', 'code');
		}
	}

	/**
	 * @param array $code_block
	 * @return string
	 */
	public static function parse(array $code_block): string
	{
		$parser = new CodeParser;
		$parser->checkPropertyExistence($code_block);

		$data = $code_block['data']['code'];
		$escaped_data = htmlspecialchars($data);

		return "<pre><code>{$escaped_data}</code></pre>";
	}
}

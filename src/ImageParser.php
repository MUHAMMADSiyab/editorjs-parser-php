<?php

namespace Siyabdev\EditorjsParserPhp;

use Siyabdev\EditorjsParserPhp\Exceptions\PropertyNotFoundException;

class ImageParser implements ShouldCheckForPropertyExistence
{
	/**
	 * @param array $image_block
	 * @throws PropertyNotFoundException
	 * @return void
	 */
	public function checkPropertyExistence(array $image_block): void
	{
		if (!array_key_exists('data', $image_block)) {
			throw new PropertyNotFoundException('image', 'data');
		}

		if (!array_key_exists('caption', $image_block['data'])) {
			throw new PropertyNotFoundException('image', 'caption');
		}

		if (!array_key_exists('file', $image_block['data'])) {
			throw new PropertyNotFoundException('image', 'file');
		}
	}

	/**
	 * @param array $image_block
	 * @return string
	 */
	public static function parse(array $image_block): string
	{
		$parser = new ImageParser;
		$parser->checkPropertyExistence($image_block);

		$url = $image_block['data']['file']['url'];
		$caption = $image_block['data']['caption'];

		return "<figure>
					<img src='{$url}' alt='{$caption}' width='100%' />
					<figcaption>{$caption}</figcaption>
				</figure>";
	}
}

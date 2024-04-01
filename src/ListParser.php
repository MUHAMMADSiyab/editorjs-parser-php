<?php

namespace MuhammadSiyab\EditorjsParserPhp;

use MuhammadSiyab\EditorjsParserPhp\Exceptions\ListItemsArrayException;
use MuhammadSiyab\EditorjsParserPhp\Exceptions\PropertyNotFoundException;

class ListParser implements ShouldCheckForPropertyExistence
{
	/**
	 * @param array $list_block
	 * @throws PropertyNotFoundException
	 * @return void
	 */
	public function checkPropertyExistence(array $list_block): void
	{
		if (!array_key_exists('data', $list_block)) {
			throw new PropertyNotFoundException('list', 'data');
		}

		if (!array_key_exists('style', $list_block['data'])) {
			throw new PropertyNotFoundException('list', 'style');
		}

		if (!array_key_exists('items', $list_block['data'])) {
			throw new PropertyNotFoundException('list', 'items');
		}
	}

	/**
	 * @param array $list_block
	 * @return string
	 */
	public static function parse(array $list_block): string
	{
		$parser = new ListParser;
		$parser->checkPropertyExistence($list_block);

		$items = $list_block['data']['items'];
		$style = $list_block['data']['style'];

		$list = $parser->isList('ordered', $style) ? '<ol>' : '<ul>';

		if (!is_array($items)) {
			throw new ListItemsArrayException("List Section: Items must be an array");
		}

		foreach ($items as $item) {
			if (!is_array($item)) {
				$list .= "<li>{$item}</li>";
			} else {
				$parser->generateNestedList($list, $item, $style);
			}
		}

		$list .= $parser->isList('ordered', $style) ? '</ol>' : '</ul>';

		return $list;
	}

	/**
	 * @param string $list
	 * @param array $item
	 * @param string $style
	 * @return void
	 */
	public function generateNestedList(string &$list, array $item, string $style): void
	{
		$this->checkNestedPropertiesExistence($item);

		$list .= "<li>{$item['content']} " . ($this->isList('ordered', $style) ? '<ol>' : '<ul>');

		foreach ($item['items'] as $items_array) {
			$this->generateNestedList($list, $items_array, $style);
		}

		$list .= ($this->isList('ordered', $style) ? '</ol>' : '</ul>') . "</li>";
	}

	/**
	 * @param array $item
	 * @throws PropertyNotFoundException|ListItemsArrayException
	 * @return void
	 */
	public function checkNestedPropertiesExistence(array $item): void
	{
		if (!array_key_exists('content', $item)) {
			throw new PropertyNotFoundException("nested list", "content");
		}

		if (!array_key_exists('items', $item)) {
			throw new PropertyNotFoundException("nested list", "items");
		}

		if (!is_array($item['items'])) {
			throw new ListItemsArrayException("Nested list Section: `items` must be an array");
		}
	}

	public function isList(string $type, string $style)
	{
		return $style === $type;
	}
}

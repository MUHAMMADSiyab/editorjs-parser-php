<?php

namespace MuhammadSiyab\EditorjsParserPhp;

use MuhammadSiyab\EditorjsParserPhp\Exceptions\PropertyNotFoundException;
use MuhammadSiyab\EditorjsParserPhp\Exceptions\TableMismatchedColumnsException;

class TableParser implements ShouldCheckForPropertyExistence
{
	/**
	 * @param array $table_block
	 * @throws PropertyNotFoundException
	 * @return void
	 */
	public function checkPropertyExistence(array $table_block): void
	{
		if (!array_key_exists('data', $table_block)) {
			throw new PropertyNotFoundException('table', 'data');
		}

		if (!array_key_exists('content', $table_block['data'])) {
			throw new PropertyNotFoundException('table', 'content');
		}
	}

	/**
	 * @param array $table_block
	 * @return string
	 */
	public static function parse(array $table_block)
	{
		$parser = new TableParser;
		$parser->checkPropertyExistence($table_block);

		$content = $table_block['data']['content'];

		if (!$parser->isContentArrayInvalid($content)) {
			return;
		}

		$table_markup = "<table border='1' cellspacing='0'><tbody>";

		foreach ($content as $row) {
			$table_markup .= "<tr>";

			foreach ($row as $column) {
				$table_markup .= "<td>{$column}</td>";
			}

			$table_markup .= "</tr>";
		}

		$table_markup .= "</tbody></table>";

		return $table_markup;
	}

	/**
	 * @param array $content
	 * @return boolean
	 */
	public function isContentArrayInvalid(array $content): bool
	{
		if (!is_array($content)) {
			return false;
		}

		if ($this->isColumnNonArray($content)) {
			return false;
		}

		if ($this->isMismatchedColumnsCount($content)) {
			throw new TableMismatchedColumnsException("Table Section: Mismatched columns count");
		}

		return true;
	}

	public function isMismatchedColumnsCount(array $content): bool
	{
		$columns_count = array_map(fn ($columns) => count($columns), $content);
		$mismatched_columns_count = array_unique($columns_count);

		return count($mismatched_columns_count) > 1;
	}

	public function isColumnNonArray(array $content): bool
	{
		return count(array_filter($content, fn ($item) => !is_array($item))) > 0;
	}
}

<?php

namespace MuhammadSiyab\EditorjsParserPhp;

use MuhammadSiyab\EditorjsParserPhp\Exceptions\BlocksNotFoundException;
use MuhammadSiyab\EditorjsParserPhp\Exceptions\PropertyNotFoundException;

class Parser
{
	private string $parsed_content = '<div class="editorjs__content">';
	private array $skip_list = [];
	private array $allow_list = [];

	/**
	 * Undocumented function
	 * @param array $content
	 * @throws BlocksNotFoundException
	 * @return void
	 */
	public function checkForBlocks(array $content): void
	{
		if (!array_key_exists('blocks', $content)) {
			throw new BlocksNotFoundException("No blocks found");
		}
	}

	/**
	 * Parse editorjs's JSON content to HTML
	 *
	 * @param string $content
	 * @return string
	 */
	public function parse(string $content): string
	{
		$parsed_content = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

		$this->checkForBlocks($parsed_content);

		$this->processParsing($parsed_content['blocks']);

		return $this->parsed_content;
	}

	/**
	 * @param array $blocks
	 * @return void
	 */
	private function processParsing(array $blocks): void
	{
		if (count($this->allow_list) > 0) {
			$blocks = $this->getAllowedBlocks($blocks);
		}

		foreach ($blocks as $block) {
			if (!array_key_exists('type', $block)) {
				throw new PropertyNotFoundException('main', 'type');
			}

			switch ($block['type']) {
				case 'paragraph':
					$this->parsed_content .=
						$this->notInSkipList('paragraph') ? ParagraphParser::parse($block) : '';
					break;
				case 'header':
					$this->parsed_content .=
						$this->notInSkipList('header') ? HeadingParser::parse($block) : '';
					break;
				case 'list':
					$this->parsed_content .=
						$this->notInSkipList('list') ? ListParser::parse($block) : '';
					break;
				case 'image':
					$this->parsed_content .=
						$this->notInSkipList('image') ? ImageParser::parse($block) : '';
					break;
				case 'code':
					$this->parsed_content .=
						$this->notInSkipList('code') ? CodeParser::parse($block) : '';
					break;
				case 'table':
					$this->parsed_content .=
						$this->notInSkipList('table') ? TableParser::parse($block) : '';
					break;
				case 'quote':
					$this->parsed_content .=
						$this->notInSkipList('quote') ? BlockQuoteParser::parse($block) : '';
					break;
				case 'embed':
					$this->parsed_content .=
						$this->notInSkipList('embed') ? EmbedParser::parse($block) : '';
					break;
			}
		}

		$this->parsed_content .= "</div>";
	}

	/**
	 * @param array $blocks
	 * @return array
	 */
	private function getAllowedBlocks(array $blocks): array
	{
		return array_filter(
			$blocks,
			fn ($block) => in_array($block['type'], $this->allow_list)
		);
	}

	/**
	 * @param string $type
	 * @return boolean
	 */
	private function notInSkipList(string $type): bool
	{
		return !in_array($type, $this->skip_list);
	}

	/**
	 * Skip blocks from being parsed
	 *
	 * @param string|array $skipped The skipped blocks
	 * @return Parser
	 */
	public function skip(string|array $skipped): Parser
	{
		if (count(func_get_args()) > 1) {
			$this->skip_list = func_get_args();
		} else {
			$this->skip_list = is_array($skipped) ? $skipped : [$skipped];
		}

		return $this;
	}

	/**
	 * Allow parsing of specific blocks
	 *
	 * @param string|array $allowed The allowed blocks
	 * @return Parser
	 */
	public function only(string|array $allowed): Parser
	{
		if (count(func_get_args()) > 1) {
			$this->allow_list = func_get_args();
		} else {
			$this->allow_list = is_array($allowed) ? $allowed : [$allowed];
		}

		return $this;
	}
}

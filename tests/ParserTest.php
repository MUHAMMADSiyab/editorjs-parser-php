<?php

use PHPUnit\Framework\TestCase;
use MuhammadSiyab\EditorjsParserPhp\BlockQuoteParser;
use MuhammadSiyab\EditorjsParserPhp\CodeParser;
use MuhammadSiyab\EditorjsParserPhp\EmbedParser;
use MuhammadSiyab\EditorjsParserPhp\HeadingParser;
use MuhammadSiyab\EditorjsParserPhp\ImageParser;
use MuhammadSiyab\EditorjsParserPhp\ListParser;
use MuhammadSiyab\EditorjsParserPhp\ParagraphParser;
use MuhammadSiyab\EditorjsParserPhp\TableParser;

/**
 @covers Parser
 */
class ParserTest extends TestCase
{
	public function test_paragraph_block_can_be_parsed_to_html()
	{
		$paragraph_block = json_decode('{
			"id": "-DikONHq7E",
			"type": "paragraph",
			"data": {
			  "text": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi."
			}
		  }', true);

		$paragraph_expected_html_output = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi.</p>';

		$parsed_html_output = ParagraphParser::parse($paragraph_block);

		$this->assertXmlStringEqualsXmlString($paragraph_expected_html_output, $parsed_html_output);
	}

	public function test_header_block_can_be_parsed_to_html()
	{
		$header_block = json_decode('{
			"id": "OOIkJmRfOL",
			"type": "header",
			"data": { "text": "Lorem Ipsum Heading", "level": 1 }
		  }', true);

		$header_expected_html_output = '<h1>Lorem Ipsum Heading</h1>';

		$parsed_html_output = HeadingParser::parse($header_block);

		$this->assertXmlStringEqualsXmlString($header_expected_html_output, $parsed_html_output);
	}

	public function test_image_block_can_be_parsed_to_html()
	{
		$image_block = json_decode('{
			"id": "VJvcTqLFpc",
			"type": "image",
			"data": {
			  "file": {
				"url": "http://127.0.0.1:8000/storage/uploads/image.png"
			  },
			  "caption": "A testing image"
			}
		  }', true);

		$image_expected_html_output = "<figure>
				<img src='http://127.0.0.1:8000/storage/uploads/image.png' alt='A testing image' width='100%' />
				<figcaption>A testing image</figcaption>
			</figure>";

		$parsed_html_output = ImageParser::parse($image_block);

		$this->assertXmlStringEqualsXmlString($image_expected_html_output, $parsed_html_output);
	}

	public function test_quote_block_can_be_parsed_to_html()
	{
		$quote_block = json_decode('{
			"type": "quote",
			"data": {
			  "text": "The only true wisdom is in knowing you know nothing. An unexamined life is not worth living. I cannot teach anybody anything. I can only make them think.",
			  "caption": "Socrates",
			  "alignment": "left"
			}
		  }', true);

		$quote_expected_html_output = "<blockquote style='text-align: left;'>
					<p><em><q>The only true wisdom is in knowing you know nothing. An unexamined life is not worth living. I cannot teach anybody anything. I can only make them think.</q></em></p>
					<footer>- Socrates</footer>
				</blockquote>";

		$parsed_html_output = BlockQuoteParser::parse($quote_block);

		$this->assertXmlStringEqualsXmlString($quote_expected_html_output, $parsed_html_output);
	}

	public function test_code_block_can_be_parsed_to_html()
	{
		$code_block = json_decode('{
			"id": "T9-fJ8rPnZ",
			"type": "code",
			"data": {
			  "code": "function(){return \\"Hello World\\"} greet();"
			}
		  }', true);

		$code_expected_html_output = '<pre><code>function(){return "Hello World"} greet();</code></pre>';

		$parsed_html_output = CodeParser::parse($code_block);

		$this->assertXmlStringEqualsXmlString($code_expected_html_output, $parsed_html_output);
	}

	public function test_table_block_can_be_parsed_to_html()
	{
		$table_block = json_decode('{
			"id": "yTfmwoOEc4",
			"type": "table",
			"data": {
			  "content": [
				["Kine", "1 pcs", "100$"],
				["Dogs", "3 pcs", "200$"],
				["Chickens", "12 pcs", "150$"],
				["Cats", "15 pcs", "50$"]
			  ]
			}
		  }', true);

		$table_expected_html_output = '<table border="1" cellspacing="0"><tbody><tr><td>Kine</td><td>1 pcs</td><td>100$</td></tr><tr><td>Dogs</td><td>3 pcs</td><td>200$</td></tr><tr><td>Chickens</td><td>12 pcs</td><td>150$</td></tr><tr><td>Cats</td><td>15 pcs</td><td>50$</td></tr></tbody></table>';

		$parsed_html_output = TableParser::parse($table_block);

		$this->assertXmlStringEqualsXmlString($table_expected_html_output, $parsed_html_output);
	}

	public function test_list_block_can_be_parsed_to_html()
	{
		$list_block = json_decode('{
			"id": "rnh__Pd01O",
			"type": "list",
			"data": {
			  "style": "ordered",
			  "items": [
				"This is the first item",
				"This is the second one",
				"Huuh, the third one",
				"No way the fourth one"
			  ]
			}
		  }', true);

		$list_expected_html_output = '<ol><li>This is the first item</li><li>This is the second one</li><li>Huuh, the third one</li><li>No way the fourth one</li></ol>';

		$parsed_html_output = ListParser::parse($list_block);

		$this->assertXmlStringEqualsXmlString($list_expected_html_output, $parsed_html_output);
	}

	public function test_nested_list_block_can_be_parsed_to_html()
	{
		$nested_list_block = json_decode('{
			"id": "rnh__Pd0655",
			"type": "list",
			"data": {
			  "style": "unordered",
			  "items": [
				{
				  "content": "Apples",
				  "items": [
					{
					  "content": "Red",
					  "items": [
						{
						  "content": "Foo",
						  "items": []
						}
					  ]
					},
					{
					  "content": "Green",
					  "items": []
					}
				  ]
				},
				{
				  "content": "Bananas",
				  "items": [
					{
					  "content": "Yellow",
					  "items": []
					}
				  ]
				}
			  ]
			}
		  }', true);

		$nested_list_expected_html_output = '<ul><li>Apples <ul><li>Red <ul><li>Foo <ul></ul></li></ul></li><li>Green <ul></ul></li></ul></li><li>Bananas <ul><li>Yellow <ul></ul></li></ul></li></ul>';

		$parsed_html_output = ListParser::parse($nested_list_block);

		$this->assertXmlStringEqualsXmlString(
			$nested_list_expected_html_output,
			$parsed_html_output
		);
	}

	public function test_embed_block_can_be_parsed_to_html()
	{
		$embed_block = json_decode(' {
			"id": "SVgWCTVw0B",
			"type": "embed",
			"data": {
			  "service": "github",
			  "source": "https://gist.github.com/MUHAMMADSiyab/d23f033304b459faa9c35e7e8d61ea30",
			  "embed": "data:text/html;charset=utf-8,<head><base target=\"_blank\" /></head><body><script src=\"https://gist.github.com/MUHAMMADSiyab/d23f033304b459faa9c35e7e8d61ea30.js\" ></script></body>",
			  "width": 600,
			  "height": 300,
			  "caption": "Amazing gist&nbsp;"
			}
		  }', true);

		$embed_expected_html_output = '<script src="https://gist.github.com/MUHAMMADSiyab/d23f033304b459faa9c35e7e8d61ea30.js"></script>';

		$parsed_html_output = EmbedParser::parse($embed_block);

		$this->assertXmlStringEqualsXmlString(
			$embed_expected_html_output,
			$parsed_html_output
		);
	}
}

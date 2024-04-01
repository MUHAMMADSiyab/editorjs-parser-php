## Editor.js Parser for PHP

This package allows you to easily parse [Editor.js](https://editorjs.io/) JSON output to HTML using PHP. This package is currently able to parse the blocks from these plugins:

- [Paragraph](https://github.com/editor-js/paragraph)
- [Header](https://github.com/editor-js/header)
- [Image](https://github.com/editor-js/image)
- [Quote](https://github.com/editor-js/quote)
- [List](https://github.com/editor-js/list)
- [Nested List](https://github.com/editor-js/nested-list)
- [Code](https://github.com/editor-js/code)
- [Embed](https://github.com/editor-js/embed) _(Currently, the following embed blocks are supported)_
  - Youtube
  - Facebook
  - Twitter/X
  - Instagram
  - Codepen
  - Github (gist)

## Requirements

PHP `^7.4`

## Installation

    composer require muhammadsiyab/editorjs-parser-php

## Using

```php

require "vendor/autoload.php";


use MuhammadSiyab\EditorjsParserPhp\Parser;


# The json output generated by Editor.js
$content = '{"time": 1711232666978,"blocks": [{...}]}' ;

$parser = new Parser();
$parsed = $parser->parse($content);

echo $parsed; // outputs the generated HTML

```

### Allow only specific blocks for parsing

```php

# Only parses the `headings` and `paragraphs`

$parsed = $parser
            ->only('header', 'paragraph') // can be parsed using the array syntax ['header', 'paragraph']
            ->parse($content);

```

### Disable specific blocks from being parsed

```php

# Parses all the blocks except `list` and `code`

$parsed = $parser
            ->except('list', 'code') // can be parsed using the array syntax ['list', 'code']
            ->parse($content);

```

### License

This Package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

# FrontYAML

An implementation of YAML Front matter for PHP. Can parse the YAML *and* the Markdown.

[![Build Status](https://travis-ci.org/mnapoli/FrontYAML.png?branch=master)](https://travis-ci.org/mnapoli/FrontYAML)

## Installation

Require the project with Composer:

```json
{
    "require": {
        "mnapoli/front-yaml": "*"
    }
}
```

## Usage

```php
$parser = new Mni\FrontYAML\Parser();

$document = $parser->parse($str);

$yaml = $document->getYAML();
$html = $document->getContent();
```

If you want the Markdown to be parsed:

```json
{
    "require": {
        "mnapoli/front-yaml": "*",
        "erusev/parsedown": "~0.8"
    }
}
```

```php
$markdown_parser = new Parsedown();
$parser = new Mni\FrontYAML\Parser(null, $markdown_parser);

$document = $parser->parse($str);
```

## Example

The following file:

```markdown
---
foo: bar
---
This is **strong**.
```

Will give:

```php
var_export($document->getYAML());
// array("foo" => "bar")

var_export($document->getContent());
// "This is **strong**."

// If a markdown parser was used:
// "<p>This is <strong>strong</strong></p>."
```



## YAML and Markdown parsers

```php
$parser = new Mni\FrontYAML\Parser($yamlParser, $markdownParser);
```

This library uses dependency injection and abstraction to allow you to provide your own YAML or Markdown parser.

```php
interface YAMLParser
{
    public function parse($yaml);
}
```

FrontYAML uses by default [Symfony's YAML parser](http://symfony.com/doc/current/components/yaml/introduction.html).

```php
interface MarkdownParser
{
    public function parse($markdown);
}
```

FrontYAML suggests [Parsedown Markdown parser](http://parsedown.org/).

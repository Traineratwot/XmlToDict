# XmlToDict

A complete analogue of XmlToDict for python, but written in PHP

Полный аналог XmlToDict для python, но написанный на PHP

# install

```bash
composer require traineratwot/xmltodict
```

## Usage

```php
$array = XmlToDict::load(__DIR__ . '/test.xml');

$xml = file_get_contents(__DIR__ . '/test.xml')
$array = XmlToDict::parse($xml);
```

## example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<books>
	<book id="1">
		<title meta="test">Harry Potter and the Philosopher's Stone</title>
		<author>J.K. Rowling</author>
		<publisher>Bloomsbury</publisher>
		<year>1997</year>
	</book>
	<book id="2">
		<title meta="test2">The Lord of the Rings</title>
		<author>J.R.R. Tolkien</author>
		<publisher>George Allen &amp; Unwin</publisher>
		<year>1954</year>
	</book>
	<item>Text</item>
	<item2 desc="Lorem">Text</item2>

	<item3></item3>
</books>
```

```json
{
    "books": {
        "book": [
            {
                "@id": "1",
                "title": {
                    "@meta": "test",
                    "#text": "Harry Potter and the Philosopher's Stone"
                },
                "author": "J.K. Rowling",
                "publisher": "Bloomsbury",
                "year": "1997"
            },
            {
                "@id": "2",
                "title": {
                    "@meta": "test2",
                    "#text": "The Lord of the Rings"
                },
                "author": "J.R.R. Tolkien",
                "publisher": "George Allen & Unwin",
                "year": "1954"
            }
        ],
        "item": "Text",
        "item2": {
            "@desc": "Lorem",
            "#text": "Text"
        },
        "item3": null
    }
}
```

## supported

php 7.3+

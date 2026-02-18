<?php
/** @noinspection SimpleXmlLoadFileUsageInspection */

namespace Traineratwot\XmlToDict;

use SimpleXMLElement;
use RuntimeException;

class XmlToDict
{
    private const ARRAY_KEY = '_++___arr__#_key___@_';
    private const TEXT_KEY = '#text';
    private const ATTR_PREFIX = '@';
    private const XMLNS_PREFIX = '@xmlns';

    private static int $counter = 0;

    /**
     * Parse XML string to associative array
     */
    public static function parse(string $xmlString): array
    {
        $xml = simplexml_load_string($xmlString);
        if ($xml === false) {
            throw new RuntimeException('Failed to parse XML string');
        }
        return self::parseXml($xml);
    }

    /**
     * Load and parse XML file
     */
    public static function load(string $filename): array
    {
        if (!file_exists($filename)) {
            throw new RuntimeException("File not found: {$filename}");
        }
        $xml = simplexml_load_file($filename);
        if ($xml === false) {
            throw new RuntimeException("Failed to parse XML file: {$filename}");
        }
        return self::parseXml($xml);
    }

    /**
     * Parse SimpleXMLElement to array
     */
    private static function parseXml(SimpleXMLElement $xml): array
    {
        self::$counter = 0;
        $root = $xml->getName();
        $content = self::xmlToArray($xml);
        return [$root => $content];
    }

    /**
     * Convert SimpleXMLElement to array recursively
     */
    private static function xmlToArray(SimpleXMLElement $xml): mixed
    {
        self::$counter++;
        $array = [];

        // Process attributes
        foreach ($xml->attributes() as $attrName => $attrValue) {
            if (!empty($attrValue)) {
                $array[self::ATTR_PREFIX . $attrName] = (string)$attrValue;
            }
        }

        // Process namespaces
        foreach ($xml->getDocNamespaces() as $attrName => $attrValue) {
            if (!empty($attrValue)) {
                $key = $attrName ? self::XMLNS_PREFIX . ':' . $attrName : self::XMLNS_PREFIX;
                $array[$key] = (string)$attrValue;
            }
        }

        // Process child elements
        $children = [];
        foreach ($xml->children() as $element) {
            $name = $element->getName();
            $children[$name][] = $element;
        }

        foreach ($children as $name => $elements) {
            if (count($elements) === 1) {
                // Single element - process normally
                $array[$name] = self::processElement($elements[0]);
            } else {
                // Multiple elements - always create array
                $array[$name] = array_map(
                    fn(SimpleXMLElement $el) => self::processElement($el),
                    $elements
                );
            }
        }

        return $array;
    }

    /**
     * Process single XML element
     */
    private static function processElement(SimpleXMLElement $element): mixed
    {
        $data = [];

        // Add attributes
        foreach ($element->attributes() as $attrName => $attrValue) {
            $data[self::ATTR_PREFIX . $attrName] = (string)$attrValue;
        }

        // Check if element has children
        if ($element->count() === 0) {
            // Leaf element
            $text = (string)$element;
            if ($text !== '') {
                $data[self::TEXT_KEY] = $text;
            }
        } else {
            // Element with children - recurse
            $data = self::xmlToArray($element);
        }

        // Simplify single text-only elements
        if (count($data) === 1 && array_key_exists(self::TEXT_KEY, $data)) {
            return $data[self::TEXT_KEY];
        }

        // Return null for empty elements
        if (empty($data)) {
            return null;
        }

        return $data;
    }
}

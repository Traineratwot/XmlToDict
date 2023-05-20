<?php
	/** @noinspection SimpleXmlLoadFileUsageInspection */

	namespace Traineratwot\XmlToDict;

	use SimpleXMLElement;

	class XmlToDict
	{
		public static $keyName = '_++___arr__#_key___@_';
		public static $counter = 0;

		static function parse($xmlString)
		: array
		{
			$xml = simplexml_load_string($xmlString);
			return self::parseXml($xml);
		}

		static function load($filename)
		: array
		{
			$xml = simplexml_load_file($filename);
			return self::parseXml($xml);
		}

		private static function parseXml(SimpleXMLElement $xml)
		{
			self::$counter = 0;
			$root          = $xml->getName();
			$content       = self::xmlToArray($xml);
			return [
				$root => $content,
			];
		}

		static function xmlToArray(SimpleXMLElement $xml)
		{
			self::$counter++;
			$array           = [];
			foreach ($xml->attributes() as $attrName => $attrValue) {
				if (!empty($attrValue)) {
					$array['@' . $attrName] = (string)$attrValue;
				}
			}
			foreach ($xml->getDocNamespaces() as $attrName => $attrValue) {
				if (!empty($attrValue)) {
					if ($attrName) {
						$array['@xmlns:' . $attrName] = (string)$attrValue;
					} else {
						$array['@xmlns'] = (string)$attrValue;
					}
				}
			}
			foreach ($xml->children() as $element) {
				$name  = $element->getName();
				$count = $element->count();
				$data  = [];
				// Add attributes to the data array
				$attributes = $element->attributes();
				foreach ($attributes as $attrName => $attrValue) {
					$data['@' . $attrName] = (string)$attrValue;
				}
				if ($element->count() === 0) {
					if ((string)$element) {
						$data['#text'] = (string)$element;
					}
				} else {
					$data = self::xmlToArray($element);
				}

				if (is_array($data) and count($data) === 1 and array_key_exists('#text', $data)) {
					$data = $data['#text'];
				}
				if(is_array($data) and empty($data)) {
					$data = null;
				}
				if (is_array($data) && array_key_exists(self::$keyName, $data)) {
					$key = $data[self::$keyName];
					unset($data[self::$keyName]);
					$array[$name][$key] = $data;
				} else {
					$array[$name][] = $data;
				}

			}
			foreach ($array as $key => $value) {
				if (is_array($value) && count($value) === 1) {
					if (array_key_exists(0, $value)) {
						$array[$key] = $value[0];
					} elseif (array_key_exists('#text', $value)) {
						$array[$key] = $value['#text'];
					}
				}
			}
			return $array;
		}
	}

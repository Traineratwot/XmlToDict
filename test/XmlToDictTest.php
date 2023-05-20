<?php
	require_once __DIR__ . '/../vendor/autoload.php';

	use PHPUnit\Framework\TestCase;
	use Traineratwot\XmlToDict\XmlToDict;

	class XmlToDictTest extends TestCase
	{
		public function test()
		{
			$a = XmlToDict::load(__DIR__ . '/test.xml');
			file_put_contents(__DIR__ . '/my_test.xml.json', json_encode($a, 256 | JSON_PRETTY_PRINT));
			$this->assertTrue(TRUE);
		}
	}
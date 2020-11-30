<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 30/11/2020
 * Time: 19:50
 */

namespace tests\unitTests;
use model\object\dataParser\PostDataParser;
use PHPUnit\Framework\TestCase;

class PostDataParserTest extends TestCase {


    private $dataToTest;
    private $postDataParser;

    public function setUp() :void {
        parent::setUp();

        // set an array with values of the data types you want to test

        $this->dataToTest = [
            'string' => "this is a string",
            'int' => 150,
            'float' => 1.45,
            'bool' => true,
            'number_string_int' => '1990',
            'number_string_float' => '33.39'
        ];

        $this->postDataParser = new PostDataParser('www.something.com?jam=strawberry&eggs=fried', $this->dataToTest);

    }

    public function testParsesStringCorrectly() {

        $value = $this->getResultFromParsedData('string');

        $this->assertTrue(is_string($value));
        $this->assertEquals($value, $this->dataToTest['string']);

    }

    public function testParsesIntCorrectly() {

        $value = $this->getResultFromParsedData('int');

        $this->assertTrue(is_int($value));
        $this->assertEquals($value, $this->dataToTest['int']);

    }

    public function testParsesFloatCorrectly() {

        $value = $this->getResultFromParsedData('float');

        $this->assertTrue(is_float($value));
        $this->assertEquals($value, $this->dataToTest['float']);

    }

    public function testParsesBoolCorrectly() {

        $value = $this->getResultFromParsedData('bool');

        $this->assertTrue(is_bool($value));
        $this->assertEquals($value, $this->dataToTest['bool']);

    }

    public function testParsesNumberStringIntCorrectly() {

        $value = $this->getResultFromParsedData('number_string_int');

        $this->assertTrue(is_int($value));
        $this->assertEquals($value, $this->dataToTest['number_string_int']);

    }

    public function testParsesNumberStringFloatCorrectly() {

        $value = $this->getResultFromParsedData('number_string_float');

        $this->assertTrue(is_float($value));
        $this->assertEquals($value, $this->dataToTest['number_string_float']);

    }

    private function getResultFromParsedData(string $keyToReturn) {

        $this->postDataParser->parseData();
        $value = $this->postDataParser->getParsedData()[$keyToReturn];

        return $value;
    }
}

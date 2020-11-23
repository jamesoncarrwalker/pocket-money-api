<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 20/05/2020
 * Time: 20:13
 */



use \PHPUnit\Framework\TestCase;

class RequestBodyJsonStringParserTest extends TestCase {

    public function testCanParseValidJsonString() {
        $queryString = '/param1/param2';
        $jsonString = "{\"jam\":\"foo\",\"bread\":\"bar\"}";
        $jsonRequestBodyParser = new \model\object\dataParser\RequestBodyJsonStringParser($queryString,$jsonString);
        $expectedResult = ['param1','param2','jam' => 'foo','bread' => 'bar'];

        $parsedData = $jsonRequestBodyParser->getParsedData();
        $this->assertEquals($expectedResult,$parsedData);
    }

    public function testCanHandleInvalidJsonString() {
        $queryString = '/param1/param2';
        $jsonString = "{\"jamfoo\",\"bread\".\"bar\"";
        $jsonRequestBodyParser = new \model\object\dataParser\RequestBodyJsonStringParser($queryString,$jsonString);

        $parsedData = $jsonRequestBodyParser->getParsedData();
        $this->assertArrayHasKey('error',$parsedData,$parsedData['error']);
    }
}


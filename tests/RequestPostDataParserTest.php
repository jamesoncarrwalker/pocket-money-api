<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 20/05/2020
 * Time: 20:13
 */



use \PHPUnit\Framework\TestCase;

class RequestPostDataParserTest extends TestCase {

    private $queryString = '/param1/param2';
    private $queryStringData = ['param1','param2'];

    public function testCanParseSimplePostData() {
        $postArray = ['jam' => 'foo', 'bread' => 'bar'];
        $postRequestParser = new \model\object\dataParser\PostDataParser($this->queryString,$postArray);
        $expectedResult = $this->addDataToExpectedArray($postArray);

        $parsedData = $postRequestParser->getParsedData();
        $this->assertEquals($expectedResult,$parsedData);
    }

    public function testCanHandleNumbersInAndOutOfStrings() {
        $postArray = ['jam' => 'foo', 'bread' => 'bar','id' => '1','ref' => '12abd34','int' => 12];
        $postRequestParser = new \model\object\dataParser\PostDataParser($this->queryString,$postArray);

        $parsedData = $postRequestParser->getParsedData();
        $this->assertEquals($parsedData['id'],1);
        $this->assertIsInt($parsedData['id']);
        $this->assertIsInt($parsedData['int']);
        $this->assertEquals($parsedData['ref'],'12abd34');
        $this->assertEquals($parsedData['int'],12);
    }

    public function testCanCopeWithEmptyArray() {
        $postArray = [];
        $postRequestParser = new \model\object\dataParser\PostDataParser($this->queryString,$postArray);
        $expectedResult = $this->addDataToExpectedArray($postArray);

        $parsedData = $postRequestParser->getParsedData();
        $this->assertEquals($expectedResult,$parsedData);
    }

    private function addDataToExpectedArray(array $data) :array {
        $returnArray = $this->queryStringData;

        foreach($data as $key => $value) {
            $returnArray[$key] = $value;
        }

        return $returnArray;
    }
}


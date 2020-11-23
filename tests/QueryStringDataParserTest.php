<?php

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 04/04/2020
 * Time: 16:18
 */
use \PHPUnit\Framework\TestCase;

class QueryStringDataParserTest extends TestCase {

    private $queryString;
    private $queryStringParser;
    private $exectedResult;
    public function setUp() :void {
        parent::setUp();
        $this->queryString = '/param1/param2&jam=foo&bread=bar';
        $this->queryStringParser = new \model\object\dataParser\QueryStringDataParser($this->queryString);
        $this->exectedResult = ['param1','param2','jam' => 'foo','bread' => 'bar'];
    }

    /**
     * @return \model\object\dataParser\QueryStringDataParser
     */
    public function testQueryStringParserIsInstantiated() {
        $this->assertNotNull($this->queryStringParser);
        $this->assertTrue($this->queryStringParser instanceof \interfaces\ParseDataInterface);
        $this->assertTrue($this->queryStringParser instanceof \model\object\dataParser\QueryStringDataParser);
        return $this->queryStringParser;
    }


    /**
     * @param \model\object\dataParser\QueryStringDataParser
     * @depends testQueryStringParserIsInstantiated
     * @return \model\object\dataParser\QueryStringDataParser
     */
    public function testRequestStringIsSet(\model\object\dataParser\QueryStringDataParser $parser) {
        $parserString = $parser->getProperty('uriString');
        $this->assertEquals($parserString,$this->queryString);
        return $parser;
    }


    /**
     * @depends testRequestStringIsSet
     * @param \model\object\dataParser\QueryStringDataParser $parser
     * @return \model\object\dataParser\QueryStringDataParser
     */

    public function testRouteParamsAreSet(\model\object\dataParser\QueryStringDataParser $parser) {
        $this->assertNotNull($parser->getProperty('uriParams'));
        $this->assertTrue(is_array($parser->getProperty('uriParams')));
        return $parser;
    }

    /**
     * @depends testRouteParamsAreSet
     * @param \model\object\dataParser\QueryStringDataParser $parser
     * @return \model\object\dataParser\QueryStringDataParser
     */
    public function testRouteParamsMatchExpected(\model\object\dataParser\QueryStringDataParser $parser) {
        $this->assertEquals($parser->getProperty('uriParams'),['param1','param2']);
        return $parser;
    }

    /**
     * @depends testRouteParamsMatchExpected
     * @param \model\object\dataParser\QueryStringDataParser $parser
     * @return \model\object\dataParser\QueryStringDataParser
     *
     */
    public function testQueryStringParamsAreSet(\model\object\dataParser\QueryStringDataParser $parser) {
        $parser->parseData();
        $this->assertNotNull($parser->getProperty('queryStringData'));
        $this->assertTrue(is_array($parser->getProperty('queryStringData')));
        return $parser;
    }

    /**
     * @depends testQueryStringParamsAreSet
     * @param \model\object\dataParser\QueryStringDataParser $parser
     * @return \model\object\dataParser\QueryStringDataParser
     *
     */

    public function testQueryStringParamsMatchExpected(\model\object\dataParser\QueryStringDataParser $parser) {

        $this->assertEquals($parser->getProperty('queryStringData'),['jam' => 'foo','bread' => 'bar']);
        return $parser;
    }


    /**
     * @depends testQueryStringParamsMatchExpected
     * @param \model\object\dataParser\QueryStringDataParser $parser
     *
     */
    public function testParsedDataMatchesExpectedResult(\model\object\dataParser\QueryStringDataParser $parser) {

        $this->assertEquals($this->exectedResult, $parser->getParsedData());
    }
}

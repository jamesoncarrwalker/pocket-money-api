<?php
use enum\RequestMethodEnums;
use factory\DataParserFactory;
use factory\RequestObjectFactory;
use model\helper\HttpDataParserFactoryType;
use model\helper\HttpRequestRawData;
use model\request\HttpGetRequestObject;

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 10/04/2020
 * Time: 15:04
 */
use \PHPUnit\Framework\TestCase;
class HttpGetRequestObjectTest extends TestCase {

     private $httpRequestObjectDev;
     private $config;

     public function setUp() :void {
         parent::setUp();
         $configFilePathFinder = new \model\finder\FilePathFinder('env.json','app/config');
         $fileReader = new \model\fileReader\JsonFileReader($configFilePathFinder);
         $this->config = new \model\object\config\JsonConfigObject($fileReader);
         $_SERVER['REQUEST_METHOD'] = 'GET';
         $_SERVER['REQUEST_URI'] = '/web_framework/param1/param/' . 'one=1&two=2&three=3';
         $_SERVER['QUERY_STRING'] = '&one=1&two=2&three=3';
         $this->httpRequestObjectDev = RequestObjectFactory::createRequestObjectFromHttpRequest(
             $this->config,
             DataParserFactory::create(
                 HttpDataParserFactoryType::getParserType(),
                 HttpRequestRawData::getRawDataForRequest(
                     RequestMethodEnums::getConstantForValue($_SERVER['REQUEST_METHOD'])
                 )
             )
         );
         $this->httpRequestObjectDev->parseUri();
     }

    public function testRequestObjectCreatedWasAGetObject() {
        $this->assertTrue($this->httpRequestObjectDev instanceof HttpGetRequestObject);
    }

    public function testRequestMethodIsSetToGet() {
        $this->assertEquals(RequestMethodEnums::GET,$this->httpRequestObjectDev->getProperty('requestMethod'));
    }

    /**
     * @depends testRequestMethodIsSetToGet
     */

    public function testRequestObjectCanAccessQueryStringData() {
        $this->assertNotNull($this->httpRequestObjectDev->getRequestData());
    }

    public function testRequestObjectQueryStringDataMatchesExpected() {
        $this->assertEquals(['one' => 1,
                            'two' => 2,
                            'three' => 3],
                            $this->httpRequestObjectDev->getRequestData());
    }

}

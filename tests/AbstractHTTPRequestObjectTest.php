<?php

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 29/03/2020
 * Time: 21:14
 */

use enum\RequestMethodEnums;
use factory\DataParserFactory;
use factory\RequestObjectFactory;
use interfaces\HTTPManagerInterface;
use model\helper\HttpDataParserFactoryType;
use model\helper\HttpRequestRawData;
use \PHPUnit\Framework\TestCase;
class AbstractHTTPRequestObjectTest extends TestCase {

    protected $config;
    protected $httpRequestObject;
    protected $httpRequestObjectDev;
    protected $httpDataParser;
    protected $fileReader;

    public function setUp() :void {
        parent::setUp();
        $configFilePathFinder = new \model\finder\FilePathFinder('env.json','app/config');
        $this->fileReader = new \model\fileReader\JsonFileReader($configFilePathFinder);
        $this->config = new \model\object\config\JsonConfigObject($this->fileReader);


        $this->setServerObject();
        $this->httpDataParser = DataParserFactory::create(
            HttpDataParserFactoryType::getParserType(),
            HttpRequestRawData::getRawDataForRequest(
                RequestMethodEnums::getConstantForValue($_SERVER['REQUEST_METHOD'])
            )
        );
        $this->httpRequestObject = RequestObjectFactory::createRequestObjectFromHttpRequest(
            $this->config,
            $this->httpDataParser
        );
        $this->httpRequestObjectDev = RequestObjectFactory::createRequestObjectFromHttpRequest(
            $this->config,
            $this->httpDataParser,
            true
        );
    }


    public function testCanInstantiateHttpRequestObject() {


        $this->assertIsObject($this->httpRequestObject);
        $this->assertIsObject($this->httpRequestObjectDev);
        $this->assertTrue($this->httpRequestObject instanceof HTTPManagerInterface);
        $this->assertTrue($this->httpRequestObjectDev instanceof HTTPManagerInterface);

        $this->httpRequestObject;
    }

    /**
     * @depends testCanInstantiateHttpRequestObject
     */
    public function testRequestObjectConfigsAreSetAndMatchExpectedConfig() {
        $devConfig = $this->httpRequestObjectDev->getProperty('config');
        $prodConfig = $this->httpRequestObject->getProperty('config');

        $this->assertNotNull($devConfig);
        $this->assertNotNull($prodConfig);

        $this->assertEquals($this->config,$devConfig);
        $this->assertEquals($this->config,$prodConfig);
    }

    /**
     * @depends testRequestObjectConfigsAreSetAndMatchExpectedConfig
     */

    public function testDataParsersAreSetAndMatchExpected() {
        $devRequestDataParser = $this->httpRequestObjectDev->getProperty('requestDataParser');
        $prodRequestDataParser = $this->httpRequestObject->getProperty('requestDataParser');

        $this->assertNotNull($devRequestDataParser);
        $this->assertNotNull($prodRequestDataParser);

        $this->assertEquals($this->httpDataParser,$devRequestDataParser);
        $this->assertEquals($this->httpDataParser,$prodRequestDataParser);
    }

    /**
     * @depends testDataParsersAreSetAndMatchExpected
     */
    public function testServerRootsAreSetCorrectlyFromConfig() {
        $roots = $this->config->getServerRoots();
        $devConfigRoot = $roots['DEV'];
        $prodConfigRoot = $roots['PROD'];

        $devServerRoot = $this->httpRequestObjectDev->getProperty('serverRoot');
        $prodServerRoot = $this->httpRequestObject->getProperty('serverRoot');

        $this->assertNotNUll($devServerRoot);
        $this->assertNotNull($prodServerRoot);

        $this->assertEquals($devConfigRoot,$devServerRoot);
        $this->assertEquals($prodConfigRoot,$prodServerRoot);
        $this->assertEquals($prodConfigRoot,$prodServerRoot);
    }

    /**
     * @depends testServerRootsAreSetCorrectlyFromConfig
     */

    public function testRequestObjectCanTrimServerRootFromUri() {
        $_SERVER['REQUEST_URI'] = '/web_framework/param1/param/2';
        $this->httpRequestObjectDev->parseUri();

        $this->assertEquals('/param1/param/2',$this->httpRequestObjectDev->getUriStringForApp());

        $_SERVER['REQUEST_URI'] = '/param1/param/2';
        $this->httpRequestObject->parseUri();
        $this->assertEquals('/param1/param/2',$this->httpRequestObject->getUriStringForApp());
    }

    /**
     * @depends testServerRootsAreSetCorrectlyFromConfig
     */

    public function testRequestObjectRemovesQueryStringParams() {
        $_SERVER['REQUEST_URI'] = '/web_framework/param1/param/2?jam=toast&sausage=egg';
        $this->httpRequestObjectDev->parseUri();

        $this->assertEquals('/param1/param/2',$this->httpRequestObjectDev->getUriStringForApp());

        $_SERVER['REQUEST_URI'] = '/param1/param/2?jam=toast&sausage=egg';
        $this->httpRequestObject->parseUri();
        $this->assertEquals('/param1/param/2',$this->httpRequestObject->getUriStringForApp());
    }

    /**
     * @depends testRequestObjectRemovesQueryStringParams
     */
    public function testRequestObjectCanSetEntryPoint() {
        $entryPointsArray = $this->config->getEntryPoints();

        foreach($entryPointsArray as $entryPoints) {
            foreach($entryPoints as $key => $value) {
                $_SERVER['REQUEST_URI'] = $key . 'param1/param/2';
                $this->httpRequestObject->parseUri();
                $this->assertEquals($value,$this->httpRequestObject->getEntryPoint());
            }
        }

        foreach($entryPointsArray as $entryPoints) {
            foreach ($entryPoints as $key => $value) {
                $_SERVER['REQUEST_URI'] = '/web_framework/' . $key . 'param1/param/2';
                $this->httpRequestObject->parseUri();
                $this->assertEquals($value, $this->httpRequestObject->getEntryPoint());
            }
        }
    }

    private function setServerObject() {
        $_SERVER =
        ["UNIQUE_ID" => "XoDNMn8AAAEAAV4GFhwAAAAC",
        "HTTP_USER_AGENT" => "PostmanRuntime/7.23.0",
        "HTTP_ACCEPT" => "*/*",
        "HTTP_CACHE_CONTROL" => "no-cache",
        "HTTP_POSTMAN_TOKEN" => "49219e16-6391-4a83-921b-f0556e08f161",
        "HTTP_HOST" => "localhost",
        "HTTP_ACCEPT_ENCODING" => "gzip, deflate, br",
        "CONTENT_LENGTH" => "0",
        "HTTP_CONNECTION" =>"keep-alive",
        "PATH" => "/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin:/Users/jamesskywalker/.rvm/bin",
        "DYLD_LIBRARY_PATH" => "/Applications/XAMPP/xamppfiles/lib",
        "SERVER_SIGNATURE" => "",
        "SERVER_SOFTWARE" => "Apache/2.4.28 (Unix) OpenSSL/1.0.2l PHP/7.1.10 mod_perl/2.0.8-dev Perl/v5.16.3",
        "SERVER_NAME" =>  "localhost",
        "SERVER_ADDR" => "80",
        "REMOTE_ADDR" => "::1",
        "DOCUMENT_ROOT" => "/Applications/XAMPP/xamppfiles/htdocs",
        "REQUEST_SCHEME" => "http",
        "CONTEXT_PREFIX" =>"",
        "CONTEXT_DOCUMENT_ROOT" => "/Applications/XAMPP/xamppfiles/htdocs",
        "SERVER_ADMIN" =>  "you@example.com",
        "SCRIPT_FILENAME" => "/Applications/XAMPP/xamppfiles/htdocs/web_framework/index.php",
        "REMOTE_PORT" => "53073",
        "GATEWAY_INTERFACE" =>  "CGI/1.1",
        "SERVER_PROTOCOL" =>  "HTTP/1.1",
        "REQUEST_METHOD" =>  "GET",
        "QUERY_STRING" => "",
        "REQUEST_URI" => "/web_framework/param1/param/2",
        "SCRIPT_NAME" => "/web_framework/index.php",
        "PHP_SELF" => "/web_framework/index.php",
        "REQUEST_TIME_FLOAT" => 1585499442.933,
        "REQUEST_TIME" => 1585499442
    ];
    }
}

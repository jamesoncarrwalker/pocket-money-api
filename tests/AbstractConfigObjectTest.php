<?php

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 02/04/2020
 * Time: 15:14
 */
use interfaces\FinderInterface;
use PHPUnit\Framework\TestCase;

class AbstractConfigObjectTest extends TestCase {

    private $finder;
    private $fileReader;
    private $expectedConfigObjectFQN;

    public function setUp() :void {
        parent::setUp();

        //.ini file
        $this->finder = new \model\finder\FilePathFinder('env.ini','app/config');
        $this->fileReader = new \model\fileReader\IniFileReader($this->finder);
        $this->expectedConfigObjectFQN = '\model\object\config\IniConfigObject';


        //.json file
//        $this->finder = new \model\finder\FilePathFinder('env.json','app/config');
//        $this->fileReader = new \model\fileReader\JsonFileReader($this->finder);
//        $this->expectedConfigObjectFQN = '\model\object\config\JsonConfigObject';

    }

    public function testCanSetFinderObject() {
        $this->assertTrue($this->finder instanceof FinderInterface);
    }

    /**
     * @depends testCanSetFinderObject
     */

    public function testFinderObjectIsConfigFinder() {
        $this->assertTrue($this->finder instanceof \model\finder\FilePathFinder);
    }

    /**
     * @depends testFinderObjectIsConfigFinder
     */

    public function testCanInstantiateConfigObject() {
        $configObject = new $this->expectedConfigObjectFQN($this->fileReader);
        $this->assertTrue($configObject instanceof \abstractClass\AbstractConfigObject);
        $this->assertTrue($configObject instanceof $this->expectedConfigObjectFQN);
        return $configObject;

    }

    /**
     * @depends testCanInstantiateConfigObject
     * @param \interfaces\ConfigInterface $configObj
     * @return \interfaces\ConfigInterface
     */

    public function testCanReadConfigDataSourceArray(\interfaces\ConfigInterface $configObj) {
        $dataSourceArray = $configObj->getDataSourceDetails();

        $this->assertIsArray($dataSourceArray);
        $this->assertTrue(isset($dataSourceArray['MYSQL']));
        $this->assertTrue(isset($dataSourceArray['MYSQL']['HOST']));
        return $configObj;
    }

    /**
     * @depends testCanReadConfigDataSourceArray
     * @param \interfaces\ConfigInterface $configObj
     * @return \interfaces\ConfigInterface
     */

    public function testCanGetConfigPathsArray(\interfaces\ConfigInterface $configObj) {
        $configPathsArray = $configObj->getConfigPaths();

        $this->assertIsArray($configPathsArray);
        $this->assertTrue(isset($configPathsArray['DEPENDENCY']));
        $this->assertTrue(isset($configPathsArray['DEPENDENCY']['CONTROLLER']));
        return $configObj;
    }

    /**
     * @depends testCanGetConfigPathsArray
     * @param \interfaces\ConfigInterface $configObj
     * @return \interfaces\ConfigInterface
     */

    public function testCanGetServerRootArray(\interfaces\ConfigInterface $configObj) {
        $serverRootArray = $configObj->getServerRoots();
        $this->assertIsArray($serverRootArray);
        $this->assertTrue(isset($serverRootArray['DEV']));
        return $configObj;
    }

    public function testCanExtendObjectToAddOwnConfigCalls() {
        $finder = new \model\finder\FilePathFinder('env.json','app/config');
        $this->fileReader = new \model\fileReader\JsonFileReader($finder);
        $this->expectedConfigObjectFQN = '\model\exampleClasses\ExampleConfigObject';

        $confObj = new $this->expectedConfigObjectFQN($this->fileReader);

        $serverRootArray = $confObj->getServerRoots();
        $this->assertIsArray($serverRootArray);
        $this->assertTrue(isset($serverRootArray['DEV']));

        $exampleArray = $confObj->getExampleArray();
        $this->assertIsArray($exampleArray);
        $this->assertTrue(isset($exampleArray['ONE']));
    }

}

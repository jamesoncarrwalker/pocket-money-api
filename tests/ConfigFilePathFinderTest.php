<?php

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 02/04/2020
 * Time: 15:36
 */
use model\finder\FilePathFinder;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigFilePathFinderTest
 */
class ConfigFilePathFinderTest extends TestCase {

    /**
     * @var string
     */
    private $configDir = 'app/config';
    /**
     * @var string
     */
    private $fileName = 'env.json';

    /**
     * @return \model\finder\FilePathFinder
     */
    public function testConfigFilePathFinderCanBeInstantiated() {
        $configFilePathFinder = new FilePathFinder($this->fileName,$this->configDir);

        $this->assertNotNull($configFilePathFinder);
        $this->assertTrue($configFilePathFinder instanceof FilePathFinder);
        return $configFilePathFinder;
    }

    /**
     * @depends testConfigFilePathFinderCanBeInstantiated
     * @param FilePathFinder $configFilePathFinder
     */

    public function testCanSetSearchParams(FilePathFinder $configFilePathFinder) {

        $this->assertTrue($configFilePathFinder->isValid());
        $configFilePathFinder->runSearch();

        $this->assertEquals('app/config/env.json',$configFilePathFinder->getResult());
    }

}

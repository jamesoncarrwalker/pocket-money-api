<?php
use interfaces\DependencyManagerInterface;
use model\testClasses\BasicObject;
use \PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 11/04/2020
 * Time: 06:47
 */
class HttpDependencyManagerTest extends TestCase {

    protected $dependencyManager;
    protected $appConfigObject;
    protected $frameworkConfigObject;
    protected $configObjectFqn;
    protected $frameworkConfigObjectFqn;
    protected $fileReader;

    public function setUp():void {
        parent::setUp();
        $this->configObjectFqn = '\model\object\config\JsonConfigObject';//for testing ini later
        $this->appConfigObject = new \model\object\config\JsonConfigObject(
            new \model\fileReader\JsonFileReader(
                new \model\finder\FilePathFinder('env.json','app/config')
            )
        );
        $this->frameworkConfigObjectFqn = '\model\object\config\FrameworkJsonConfig';//for testing ini later
        $this->frameworkConfigObject = new \model\object\config\FrameworkJsonConfig(
            new \model\fileReader\JsonFileReader(
                new \model\finder\FilePathFinder('test_env.json','framework/config')
            )
        );
        $this->fileReader = new \model\fileReader\JsonFileReader(
          new \model\finder\FilePathFinder("")
        );
    }

    /**
     * @return \model\dependencyManager\HttpDependencyManager
     */
    public function testCanInstantiateHttpDependencyManager() {
        $this->dependencyManager = new \model\dependencyManager\HttpDependencyManager(
            $this->frameworkConfigObject,
            $this->appConfigObject,
            $this->fileReader);
        $this->assertTrue($this->dependencyManager instanceof DependencyManagerInterface);
        return $this->dependencyManager;
    }

    /**
     * @depends testCanInstantiateHttpDependencyManager
     * @param DependencyManagerInterface $depMan
     * @return DependencyManagerInterface
     */

    public function testDependencyManagerAppConfigObjectIsSet(DependencyManagerInterface $depMan) {
        $this->assertNotNull($depMan->getProperty('appConfigObject'));
        $this->assertTrue($depMan->getProperty('appConfigObject') instanceof \interfaces\ConfigInterface);
        $this->assertTrue($depMan->getProperty('appConfigObject') instanceof $this->configObjectFqn);
        return $depMan;
    }

    /**
     * @depends testDependencyManagerAppConfigObjectIsSet
     * @param DependencyManagerInterface $depMan
     * @return DependencyManagerInterface
     */
    public function testDependencyManagerFrameworkConfigObjectIsSet(DependencyManagerInterface $depMan) {
        $this->assertNotNull($depMan->getProperty('frameworkConfigObject'));
        $this->assertTrue($depMan->getProperty('frameworkConfigObject') instanceof \interfaces\ConfigInterface);
        $this->assertTrue($depMan->getProperty('frameworkConfigObject') instanceof $this->frameworkConfigObjectFqn);
        return $depMan;
    }

    /**
     * @depends testDependencyManagerFrameworkConfigObjectIsSet
     * @param DependencyManagerInterface $depMan
     * @return DependencyManagerInterface
     */
    public function testDependencyManagerCanSetDependencyFileLocationsFromConfigObjects(DependencyManagerInterface $depMan) {
        $filePathsArray = $depMan->getProperty('frameworkDependencyFileLocations');
        $this->assertIsArray($filePathsArray);
        $this->assertContains('framework/config/TestDependencies.json',$filePathsArray);

        $filePathsArray = $depMan->getProperty('appDependencyFileLocations');
        $this->assertIsArray($filePathsArray);
        $this->assertContains('app/config/dependencies/ObjectDependencies.json',$filePathsArray);

        return $depMan;
    }

    /**
     * @depends testDependencyManagerCanSetDependencyFileLocationsFromConfigObjects
     * @param DependencyManagerInterface $depMan
     * @return DependencyManagerInterface
     */
    public function testDependencyManagerCanSetListOfAllClassesWithDependencies(DependencyManagerInterface $depMan) {
        $classListArray = $depMan->getAllClassesWithDependenciesList();

        $this->assertIsArray($classListArray);

        $this->assertArrayHasKey('model\testClasses\ObjectWithBasicObjectDependency',$classListArray);
        $this->assertArrayHasKey('model\exampleClasses\ExampleConfigObject',$classListArray);
        return $depMan;

    }

    /**
     * @depends testDependencyManagerCanSetListOfAllClassesWithDependencies
     * @param DependencyManagerInterface $depMan
     * @return DependencyManagerInterface
     */
    public function testDependencyManagerCanCheckIfClassesHaveDependencies(DependencyManagerInterface $depMan) {
        $this->assertTrue($depMan->hasDependencies('model\testClasses\ObjectWithBasicObjectDependency'));
        $this->assertFalse($depMan->hasDependencies('randomNamespace\WebFrontController'));

        return $depMan;

    }

    /**
     * @depends testDependencyManagerCanCheckIfClassesHaveDependencies
     * @param DependencyManagerInterface $depMan
     * @return DependencyManagerInterface
     */
    public function testDependencyManagerCanCheckGetDependencyListForClass(DependencyManagerInterface $depMan) {

        $validClass = $depMan->getDependenciesListForClass('model\testClasses\ObjectWithBasicObjectDependency');
        $this->assertIsArray($validClass);
        $this->assertArrayHasKey('BasicObject',$validClass);

        $invalidClass = $depMan->getDependenciesListForClass('frControlr\WebFrontController');
        $this->assertIsArray($invalidClass);
        $this->assertTrue(count($invalidClass) === 0);

        return $depMan;

    }

    /**
     * @depends testDependencyManagerCanCheckGetDependencyListForClass
     * @param DependencyManagerInterface $depMan
     * @return DependencyManagerInterface
     */
    public function testCanInstantiateAnObjectWithNoDependencies(DependencyManagerInterface $depMan) {
        $basicObject = new BasicObject(...$depMan->getDependencies('model\testClasses\BasicObject'));
        $this->assertNotNull($basicObject);
        $this->assertTrue($basicObject->getBool());
        return $depMan;
    }

    /**
     * @depends testCanInstantiateAnObjectWithNoDependencies
     * @param DependencyManagerInterface $depMan
     * @return DependencyManagerInterface
     */
    public function testCanInstantiateAnObjectWithSingleObjectDependency(DependencyManagerInterface $depMan) {
        $objectWithDependency = new \model\testClasses\ObjectWithBasicObjectDependency(...$depMan->getDependencies('model\testClasses\ObjectWithBasicObjectDependency'));

        $this->assertNotNull($objectWithDependency);
        $this->assertTrue($objectWithDependency->getBoolFromBasicObject());
        return $depMan;

    }

    /**
     * @depends testCanInstantiateAnObjectWithSingleObjectDependency
     * @param DependencyManagerInterface $depMan
     * @return DependencyManagerInterface
     */
    public function testCanInstantiateAnObjectWithAStringVariableDependency(DependencyManagerInterface $depMan) {
        $obj = new \model\testClasses\ObjectWithRequestStringDependency(...$depMan->getDependencies('model\testClasses\ObjectWithRequestStringDependency'));

        $this->assertNotNull($obj);
        $this->assertEquals('default_string', $obj->getStringValue());

        $_REQUEST['url_string_variable'] = "jam on toast";
        $obj = new \model\testClasses\ObjectWithRequestStringDependency(...$depMan->getDependencies('model\testClasses\ObjectWithRequestStringDependency'));

        $this->assertNotNull($obj);
        $this->assertEquals('jam on toast', $obj->getStringValue());
        return $depMan;
    }

    /**
     * @depends testCanInstantiateAnObjectWithAStringVariableDependency
     * @return DependencyManagerInterface
     */

    public function testCanInstantiateObjectWithMultipleNestedDependencies() {
        $depManager = new \model\dependencyManager\HttpDependencyManager(
            $this->frameworkConfigObject,
            $this->appConfigObject,
            $this->fileReader);
        $obj = new model\authenticator\AuthenticatorWeb(...$depManager->getDependencies('model\authenticator\AuthenticatorWeb'));

        $this->assertNotNull($obj);
        $this->assertTrue($obj instanceof model\authenticator\AuthenticatorWeb);
        return $depManager;
    }

    /**
     * @param DependencyManagerInterface $depMan
     * @depends testCanInstantiateObjectWithMultipleNestedDependencies
     */

    public function testCanInstantiateAnObjectWhichExtendsFromAnother(DependencyManagerInterface $depMan) {

    }

    //go on to check the values in the array to make sure defaults are set



}

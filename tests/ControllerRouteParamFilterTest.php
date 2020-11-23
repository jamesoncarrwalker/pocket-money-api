<?php

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 24/05/2020
 * Time: 06:42
 */
use model\helper\ControllerDataParamsFilter;
use model\helper\DumpVars;
use \PHPUnit\Framework\TestCase;

class ControllerRouteParamFilterTest extends TestCase {

    public function testControllerParamsWhichAreDataNotRouteInfoAreExtracted() {
        $requestData = ['jam','strawberry','post_1' => '1','post_2' => 2];
        $routePattern = '/jam/[flavour]';

        $expectedOutput = ['strawberry'];


        $controllerDataParams = ControllerDataParamsFilter::getControllerDataParams($routePattern,$requestData);

        $this->assertIsArray($controllerDataParams);

        $this->assertEquals($expectedOutput,$controllerDataParams);

    }

    public function testNestedontrollerParamsWhichAreDataNotRouteInfoAreExtracted() {
        $requestData = ['jam','strawberry','order',2,'post_1' => '1','post_2' => 2];
        $routePattern = '/jam/[flavour]/order/[quantity]';

        $expectedOutput = ['strawberry',2];

        $controllerDataParams = ControllerDataParamsFilter::getControllerDataParams($routePattern,$requestData);

        $this->assertIsArray($controllerDataParams);

        $this->assertEquals($expectedOutput,$controllerDataParams);
    }

    public function testNestedontrollerParamsWhichAreDataNotRouteInfoButHaveRouteInfoNameAreExtracted() {
        $requestData = ['jam','jam','order',2,'post_1' => '1','post_2' => 2];
        $routePattern = '/jam/[flavour]/order/[quantity]';

        $expectedOutput = ['jam',2];

        $controllerDataParams = ControllerDataParamsFilter::getControllerDataParams($routePattern,$requestData);

        $this->assertIsArray($controllerDataParams);

        $this->assertEquals($expectedOutput,$controllerDataParams);
    }

    public function testNestedontrollerParamsWhichAreIntsDontKillTheScript() {
        $requestData = ['jam','jam',1,2,'post_1' => '1','post_2' => 2];
        $routePattern = '/jam/[flavour]/1/[quantity]';

        $expectedOutput = ['jam',2];

        $controllerDataParams = ControllerDataParamsFilter::getControllerDataParams($routePattern,$requestData);

        $this->assertIsArray($controllerDataParams);

        $this->assertEquals($expectedOutput,$controllerDataParams);
    }
}

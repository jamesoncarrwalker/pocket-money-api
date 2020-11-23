<?php

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 30/04/2020
 * Time: 19:36
 */
use model\service\RouteFinderService;
use PHPUnit\Framework\TestCase;
class RouteFinderServiceTest extends TestCase {

    protected $fileReader;

    public function setUp():void {
        parent::setUp();
        $this->fileReader = new \model\fileReader\JsonFileReader(new \model\finder\FilePathFinder("app/config/routes/web/routes.json"));
    }
    public function testCanInstantiateRouteFinderService() {
        $routeFinderService = new RouteFinderService($this->fileReader,[],\enum\RequestTypeEnum::WEB);
        $this->assertTrue($routeFinderService instanceof RouteFinderService);
        return $routeFinderService;
    }


    /**
     * @param RouteFinderService $routeFinderService
     * @depends testCanInstantiateRouteFinderService
     * @return RouteFinderService
     */

    public function testCanGetRouteFromRequestWithNoParamsWithRouteFileEntry(RouteFinderService $routeFinderService) {
        $routeFinderService->setParams('requestData',['login']);
        $route = $routeFinderService->getResult();

        $this->assertNotNull($route);
        $this->assertEquals('controller\web\LoginController',$route->getRouteController());
        return $routeFinderService;
    }

    /**
     * @depends testCanGetRouteFromRequestWithNoParamsWithRouteFileEntry
     * @param RouteFinderService $routeFinderService
     * @return RouteFinderService
     */
    public function testCanGetRouteFromRequestWithNoParamsWithNoRouteFileEntry(RouteFinderService $routeFinderService) {
        $routeFinderService->setParams('requestData',['profile']);
        $route = $routeFinderService->getResult();
        $this->assertNotNull($route);
        $this->assertEquals('controller\web\ProfileController',$route->getRouteController());
        return $routeFinderService;
    }

    /**
     *
     * @depends testCanInstantiateRouteFinderService
     * @param RouteFinderService $routeFinderService
     * @return RouteFinderService
     */

    public function testCanGetRouteFromRequestWithControllerAndParam(RouteFinderService $routeFinderService) {
        $routeFinderService->setParams('requestData',['register','12345']);
        $route = $routeFinderService->getResult();
        $this->assertNotNull($route);

        $this->assertEquals('controller\web\RegisterController',$route->getRouteController());
        $this->assertEquals('continueRegistration',$route->getRouteControllerMethod());
        return $routeFinderService;
    }

    /**
     *
     * @depends testCanGetRouteFromRequestWithControllerAndParam
     * @param RouteFinderService $routeFinderService
     * @return RouteFinderService
     */

    public function testCanGetRouteFromRequestWithControllerAndMultipleParams(RouteFinderService $routeFinderService) {
        $routeFinderService->setParams('requestData',['register','12345','interests','section']);
        $route = $routeFinderService->getResult();
        $this->assertNotNull($route);

        $this->assertEquals('controller\web\ReviewInterestsController',$route->getRouteController());
        return $routeFinderService;
    }

    /**
     *
     * @depends testCanGetRouteFromRequestWithControllerAndMultipleParams
     * @param RouteFinderService $routeFinderService
     * @return RouteFinderService
     */

    public function testCanGetRouteFromRequestWithValueParamValue(RouteFinderService $routeFinderService){
        $routeFinderService->setParams('requestData',['register','12345','interests']);
        $route = $routeFinderService->getResult();
        $this->assertNotNull($route);

        $this->assertEquals('controller\web\RegisterInterestsController',$route->getRouteController());

        $routeFinderService->setParams('requestData',['register','12345','jam']);
        $route = $routeFinderService->getResult();
        $this->assertNotNull($route);

        $this->assertEquals('controller\web\RegisterInterestsController',$route->getRouteController());
        return $routeFinderService;
    }

    public function /*test*/ canChangeRouteFileAndGetNewRoute() {

    }

}

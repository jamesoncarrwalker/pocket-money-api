<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 20/05/2020
 * Time: 19:41
 */

namespace controller\web;


use abstractClass\AbstractHttpRequestObject;
use abstractClass\AbstractWebController;
use enum\ContainerContentsEnum;
use model\container\WebContainer;

class RestTestController extends AbstractWebController {

    public function __construct(WebContainer $container, $requestAction) {
        parent::__construct($container, $requestAction);


        $request = $this->container->getStateVariable(ContainerContentsEnum::REQUEST);
//        if($request instanceof AbstractHttpRequestObject){
            $this->setData('RequestData',$request->getRequestData());
//        }

    }

    public function get() {
        $this->setData('Where','RestTestController');
        $this->setData('Method','GET');
        $this->setData('RequestMethod',$_SERVER['REQUEST_METHOD']);
    }

    public function post() {
        $this->setData('Where','RestTestController');
        $this->setData('Method','POST');
        $this->setData('RequestMethod',$_SERVER['REQUEST_METHOD']);
    }

    public function put(string $name) {
        $this->setData('Where','RestTestController');
        $this->setData('Method','PUT');
        $this->setData('name',$name);
        $this->setData('RequestMethod',$_SERVER['REQUEST_METHOD']);
    }

    public function patch(string $name, int $id) {
        $this->setData('Where','RestTestController');
        $this->setData('Method','PATCH');
        $this->setData('name',$name);
        $this->setData('id',$id);
        $this->setData('RequestMethod',$_SERVER['REQUEST_METHOD']);
    }

    public function delete() {
        $this->setData('Where','RestTestController');
        $this->setData('Method','DELETE');
        $this->setData('RequestMethod',$_SERVER['REQUEST_METHOD']);
    }
}
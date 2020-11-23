<?php
namespace controller\api;
use abstractClass\AbstractApiController;

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 21/05/2020
 * Time: 19:53
 */
class TestApiController extends AbstractApiController {

    public function get() {
        $this->setData("TestApiController",true);
    }

}
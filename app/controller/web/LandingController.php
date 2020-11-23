<?php

namespace controller\web;

use abstractClass\AbstractWebController;
use enum\ContainerContentsEnum;
use model\datasource\DBPdo;

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 12/10/2019
 * Time: 13:47
 */
class LandingController extends AbstractWebController {


    public function get() {
        $dbo = new DBPdo();
        $this->setData('dbo', $dbo->openConnection());
        $this->setData('message', 'Hello Fergo');
        $this->setTemplate('example');
    }

    public function post() {
        $this->setData("postRequest",true);
        $this->setData("postData", $this->getAllRequestData());
    }

}
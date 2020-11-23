<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 09/11/2019
 * Time: 21:52
 */

namespace controller\web;
use abstractClass\AbstractWebController;



class ForbiddenController extends AbstractWebController {

    protected function load() {
        $this->setData('message', 'this is the unauthed controller');
    }
}
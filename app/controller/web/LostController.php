<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 09/11/2019
 * Time: 21:52
 */

namespace controller\web;
use abstractClass\AbstractWebController;



class LostController extends AbstractWebController {

    public function get() {
        $this->setData('message', 'this is the lost controller');
    }
}
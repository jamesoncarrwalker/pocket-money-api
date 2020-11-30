<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 29/11/2020
 * Time: 19:51
 */

namespace model\abstractClass;


use abstractClass\AbstractObjectManager;
use interfaces\ObjectManagerInterface;

abstract class AbstractObject extends AbstractObjectManager implements ObjectManagerInterface{

    public static $requiredFields;

}
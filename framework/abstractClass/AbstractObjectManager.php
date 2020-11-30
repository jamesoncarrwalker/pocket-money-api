<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 30/03/2020
 * Time: 12:34
 */

namespace abstractClass;


use interfaces\ObjectManagerInterface;

abstract class AbstractObjectManager implements ObjectManagerInterface{

    public function getProperty(string $name) {
        if(property_exists($this,$name)) {
            return $this->{$name};
        } else {
            $this->throwException($name);
        }

    }

    public function setProperty(string $name, $value) {
        if(property_exists($this,$name)) {
            $this->{$name} = $value;
        } else {
            $this->throwException($name);
        }
    }

    public function getObjectVars() {
        return get_object_vars($this);
    }

    public function getObject() {
        return $this;
    }

    public function resetObject() {

    }

    public function getObjectName() {
        return get_class($this);
    }

    private function throwException(string $propertyName) {
        throw new \Exception($propertyName . ' is not a property of this object');
    }

}
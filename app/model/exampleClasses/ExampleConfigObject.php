<?php

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 03/04/2020
 * Time: 16:02
 */
namespace model\exampleClasses;

use model\object\config\JsonConfigObject;

class ExampleConfigObject extends JsonConfigObject {

    public function getExampleArray() {
        return $this->configFileContents['EXAMPLE'];
    }

}
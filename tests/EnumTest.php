<?php

use enum\DependencyTypeEnum;
use \PHPUnit\Framework\TestCase;
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 02/11/2019
 * Time: 09:18
 */
class EnumTest extends TestCase {

    public function testCanGetEnumConstant() {
        $enumConstant = DependencyTypeEnum::WEB_CONTROLLER;

        $this->assertEquals($enumConstant,DependencyTypeEnum::WEB_CONTROLLER);
    }

    public function testCanGetValueForEnumConstant() {
        $constantValue = 'web controller dependency';
        $this->assertEquals($constantValue,DependencyTypeEnum::getValueForConstant(DependencyTypeEnum::WEB_CONTROLLER));
    }

    public function testCanGetConstantFromValue() {
        $constantValue = 'web controller dependency';

        $this->assertEquals(DependencyTypeEnum::WEB_CONTROLLER,DependencyTypeEnum::getConstantForValue($constantValue));
    }

    public function testCannotGetConstantFromRandomValue() {
        $value = DependencyTypeEnum::getConstantForValue('jamandeggs');

        $this->assertNull($value);
    }

    public function testCannotGetValueFromRandomConstant() {
        $value = DependencyTypeEnum::getValueForConstant('lizzyface');
        $this->assertNull($value);

    }

    public function testCanChangeEnumAndStillGetCorrectValues() {
        $constantValue = 1;
        $this->assertEquals($constantValue,\enum\AuthLevelEnum::getValueForConstant(\enum\AuthLevelEnum::LOGIN));
    }
}

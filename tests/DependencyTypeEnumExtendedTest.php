<?php
use enum\DependencyTypeEnum;
use \PHPUnit\Framework\TestCase;
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 02/11/2019
 * Time: 12:30
 */
class DependencyTypeEnumExtendedTest extends TestCase {

    public function CanGetEnumConstant() {
        $enumConstant = DependencyTypeEnum::WEB_CONTROLLER;

        $this->assertEquals($enumConstant,DependencyTypeEnum::WEB_CONTROLLER);
    }

    public function testCanGetValueForEnumConstant() {
        $constantValue = 'web controller dependency';
        $valueReturned = DependencyTypeEnum::getValueForConstant(DependencyTypeEnum::WEB_CONTROLLER);
        $this->assertIsString( $valueReturned);
        $this->assertEquals($constantValue, $valueReturned);
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
}

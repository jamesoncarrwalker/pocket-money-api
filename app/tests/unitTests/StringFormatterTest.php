<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 26/11/2020
 * Time: 20:26
 */
namespace tests\unitTests;
use helper\StringFormatter;
use PHPUnit\Framework\TestCase;


class StringFormatterTest extends TestCase {


    public function testCanParseStringWithTagPrefix() {

        $sql = "SELECT * FROM table WHERE id = :id AND name = :name AND jam = :jam";
        $expectedResult = "SELECT * FROM table WHERE id = :value_1 AND name = :value_2 AND jam = :value_3";

        $formattedString = StringFormatter::replaceOccurrencesWithTagPrefix($sql,':','value');

        $this->assertEquals($formattedString,$expectedResult);


    }

    public function testOnlyParsesSubstringsStartingWithTagPrefix() {


        $sql = "SELECT * FROM table WHERE id = :id AND name = name AND jam = :jam";
        $expectedResult = "SELECT * FROM table WHERE id = :value_1 AND name = name AND jam = :value_2";

        $formattedString = StringFormatter::replaceOccurrencesWithTagPrefix($sql,':','value');

        $this->assertEquals($formattedString,$expectedResult);

        $sql = "SELECT * FROM table WHERE id = :id AND name = name AND jam = :jam";
        $expectedResult = "SELECT * FROM table WHERE id = :value_1 AND name = name AND jam = :value_2";

        $formattedString = StringFormatter::replaceOccurrencesWithTagPrefix($sql,':','value');

        $this->assertEquals($formattedString,$expectedResult);

    }

    public function testReturnsSameStringWhenNoPrefixExists() {

        $sql = "SELECT * FROM table WHERE id = id AND name = name AND jam = jam";
        $expectedResult = "SELECT * FROM table WHERE id = id AND name = name AND jam = jam";

        $formattedString = StringFormatter::replaceOccurrencesWithTagPrefix($sql,':','value');

        $this->assertEquals($formattedString,$expectedResult);

    }

}

<?php

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 02/05/2020
 * Time: 20:22
 */
use PHPUnit\Framework\TestCase;
class PatternMatcherTest extends TestCase {

    public function testCanReplaceSectionsOfAString() {
        $string = '/hello/[name]/is/[something]';
        $openingChar = '[';
        $closingChar = ']';
        $replacementString = 'PARAM';
        $offset = 1;

        $replacedString = \model\helper\PatternMatcher::replaceMarkedUpSectionsOfString($string,$openingChar,$closingChar,$replacementString,$offset);


        $this->assertEquals('/hello/PARAM/is/PARAM',$replacedString);
    }
}

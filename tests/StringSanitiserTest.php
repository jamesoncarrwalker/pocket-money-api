<?php

/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 10/05/2020
 * Time: 16:14
 */
use PHPUnit\Framework\TestCase;

class StringSanitizerTest extends TestCase {

    public function testCanReplaceNewLinesWithBreaks() {
        $originalString = "This is \r a new \n line";
        $expectedResult = "This is <br> a new <br> line";

        $cleanString = \model\helper\StringSanitiser::sanitise($originalString);

        $this->assertEquals($expectedResult,$cleanString);
    }

    public function testCanStripTagsFromString() {
        $originalString = "This is an <script src='scriptofdoom.com'></script>XSS <b>string</b>";
        $expectedResult = "This is an XSS string";

        $cleanString = \model\helper\StringSanitiser::sanitise($originalString);

        $this->assertEquals($expectedResult,$cleanString);
    }

    public function testCanStringHtmlEncodedTags() {
        $originalString = "This is an &lt;script src='scriptofdoom.com'&gt;&lt;/script&gt; XSS <b>string</b>";
        $expectedResult = "This is an script ='scriptofdoom.com'/script XSS string";

        $cleanString = \model\helper\StringSanitiser::sanitise($originalString);

        $this->assertEquals($expectedResult,$cleanString);
    }
}

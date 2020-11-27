<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 26/11/2020
 * Time: 20:05
 */

namespace helper;


use model\helper\StringSanitiser;
use model\helper\Util;

class StringFormatter {

    public static function replaceOccurrencesWithTagPrefix(string $stringToFormat, string $tagToSearchFor, string $replacementValue, int $offset = 0) :string {
        $i = 0;

        $stringToFormat = $stringToFormat . " ";

        while(strpos($stringToFormat, $tagToSearchFor, $offset) !== false) {

            // get the templateData[$key] to look for
            $rawDataValue = StringSanitiser::sanitise(Util::getSubstringFromTags($stringToFormat, $tagToSearchFor, ' ', $offset));

            if(!$rawDataValue) {
                break;
            }

            $i++;

            //add the tags back on for the S&R
            $stringToReplace = $tagToSearchFor . trim($rawDataValue);

            $newString = $tagToSearchFor . $replacementValue . '_' . $i;

            $stringToFormat = str_replace($stringToReplace, $newString, $stringToFormat);

            $offset = (strpos($stringToFormat,$tagToSearchFor, $offset) + 1);

        }

        return trim($stringToFormat);

    }
}
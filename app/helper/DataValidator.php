<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 29/11/2020
 * Time: 20:35
 */

namespace helper;


use model\helper\DumpVars;

class DataValidator {

    public static function checkRequiredDataExists(string $className, array $data) {

        $requiredData = $className::$requiredFields ?? [];
        $missingData = array_diff($requiredData,$data);
        return count($missingData) == 0;

    }

    public static function getRequiredDataForClassFromArray(string $className, array $data) {

        $requiredData = $className::$requiredFields ?? [];
        if(count($requiredData) > 0) {

            $comparisonArray = array_combine($requiredData,range(1,count($requiredData)));
            $replacedArray = array_replace($requiredData, $data);


            $filteredData = array_intersect_key($replacedArray, $comparisonArray);

            return $filteredData;

        }
        return [];

    }

    public static function getOptionalDataForClassFromArray(string $className, array $data) {

        $requiredData = $className::$optionalFields ?? [];

        $filteredData = array_intersect_key($data, $requiredData);

        return $filteredData;

    }

    public static function getCompleteDataForClassFromArray(string $className, array $data) {

//        $requiredData = $className.'::$optionalFields' ?? [];
//
//        $filteredData = array_intersect_key($data, $requiredData);
//
//        return $filteredData;

    }
}
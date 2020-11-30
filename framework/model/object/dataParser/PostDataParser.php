<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 15/09/2020
 * Time: 21:07
 */

namespace model\object\dataParser;


use abstractClass\AbstractHttpRequestDataParser;
use model\helper\StringSanitiser;

class PostDataParser extends AbstractHttpRequestDataParser {

    private $postData;
    private $parsedPostData;

    public function __construct(string $uriString, array $postData) {
        parent::__construct($uriString);

        $this->postData = $postData;
    }
    public function parseData() {

        if(count($this->postData) > 0) {
            foreach($this->postData as $key => $value) {

                //check if the value is a number or a number string
                if(is_numeric($value)) {
                    //convert it to a string just in case, so we don't loose any decimal values
                    $value = (string) $value;
                    //check for decimals and cast accordingly
                    $value = strpos($value,'.') ? (float) $value : (int) $value;

                } else if (is_string($value)) {
                    //if it is a string the sanitize if
                    $value = StringSanitiser::sanitise($value);
                }
                // otherwise leave it alone

                $this->parsedPostData[$key] = $value;
            }
        } else {
            $this->parsedPostData = [];
        }


    }

    public function getParsedData() {

        if(!isset($this->parsedPostData)) {
            $this->parseData();
        }

        return array_merge($this->uriParams, $this->parsedPostData);
    }
}
<?php
namespace model\response;


use abstractClass\AbstractHttpResponseObject;
use interfaces\TemplateInterface;


/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 12/10/2019
 * Time: 19:39
 */
class WebResponseObject extends AbstractHttpResponseObject {

    private $templateParser;
    private $templateName;


    public function __construct(TemplateInterface $templateParser) {
        $this->templateParser = $templateParser;
    }

    public function setResponse() {
        if (!isset($this->templateName)) {
            $this->response = json_encode($this->responseData??[]);
        } else {
            $this->templateParser->setTemplate($this->templateName, $this->responseData);
            $this->response = $this->templateParser->parseString();
        }
    }

    public function setResponseData(string $name, $data) {
        if ($name == 'template') {
            $this->templateName = $data;
        } else if ($name == 'header') {
            $this->headers[] = $data;
        } else {
            $this->responseData[$name] = $data;
        }

    }


    public function setBasicHeaders() {
        //implement the headers you need
        if(!isset($this->templateName)) {
            $this->headers[] = 'Content-Type: application/json';
            $this->headers[] = 'Access-Control-Allow-Origin: *';
        }
    }
}
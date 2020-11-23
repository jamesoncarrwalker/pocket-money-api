<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 20/09/2019
 * Time: 15:05
 */

use enum\RequestMethodEnums;
use factory\DataParserFactory;
use factory\FrontControllerFactory;
use factory\RequestObjectFactory;
use interfaces\ResponseHeadersManagerInterface;
use model\helper\HttpDataParserFactoryType;
use model\helper\HttpRequestRawData;
use model\object\config\JsonConfigObject;

/**
 * set up the autoloader
 * @return bool
 */

function setAutoLoader() {
    return spl_autoload_register(
        function($class) {
            $coreClass = 'framework/' .  str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            $appClass = 'app/' .  str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            $testClass = 'tests/' .  str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

            if(file_exists($coreClass)) include_once $coreClass;
            else if(file_exists($appClass)) include_once $appClass;
            else if(file_exists($testClass)) include_once $testClass;
        }
    );
}

setAutoLoader();

/**
 *
 * get the app config object
 *
 */
if(file_exists('app/config/env.json')) {
    $jsonFileReader = new \model\fileReader\JsonFileReader( new \model\finder\FilePathFinder('env.json','app/config'));
    $appConfigObj = new JsonConfigObject($jsonFileReader);
} else if (file_exists('app/config/env.ini')) {
    $iniFileReader = new \model\fileReader\IniFileReader( new \model\finder\FilePathFinder('env.ini','app/config'));
    $appConfigObj = new JsonConfigObject($iniFileReader);
} else {
    die('no config set');
}


/**
 *
 * get the framework config object
 *
 */

$jsonFileReader->setFile('framework/config/env.json');
$frameworkConfigObj = new JsonConfigObject($jsonFileReader);

/**
 *
 * set the custom error handler
 *
 */

$errorHandler = $appConfigObj->getErrorHandler();
if(!isset($errorHandler) || $errorHandler == "") $errorHandler = $frameworkConfigObj->getErrorHandler();
if(isset($errorHandler) && $errorHandler != "") {
    include_once($errorHandler);
}


/**
 *
 * get the request object for the front controller factory
 *
 */

$httpRequestObject = RequestObjectFactory::createRequestObjectFromHttpRequest(
    $appConfigObj,
    DataParserFactory::create(
        HttpDataParserFactoryType::getParserType(),
            HttpRequestRawData::getRawDataForRequest(
                RequestMethodEnums::getConstantForValue($_SERVER['REQUEST_METHOD'])
            )
        ),
        ($_SERVER['DOCUMENT_ROOT'] == "/Applications/XAMPP/xamppfiles/htdocs")
    );

/**
 *
 * create a front controller
 *
 */

$jsonFileReader->setFile("");

$frontController = FrontControllerFactory::createHttpFrontController($httpRequestObject, $frameworkConfigObj, $appConfigObj,$jsonFileReader);



/**
 *
 * run the request
 *
 */

$frontController->runRequest();

/**
 *
 * get the response object
 *
 */

$response = $frontController->getResponse();

/**
 *
 * if we have defined any specific header information the make sure we set it
 *
 */
if($response instanceof ResponseHeadersManagerInterface) $response->setHeaders();

/**
 *
 * output the response
 *
 */

$response->outputResponse();

/**
 *
 * end the script
 *
 */
die();




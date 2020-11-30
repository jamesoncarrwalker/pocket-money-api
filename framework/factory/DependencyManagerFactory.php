<?php
/**
 * Created by PhpStorm.
 * User: jamesskywalker
 * Date: 29/03/2020
 * Time: 13:10
 */

namespace factory;


use enum\RequestTypeEnum;
use interfaces\ConfigInterface;
use interfaces\FileParseInterface;
use interfaces\HTTPManagerInterface;
use model\authenticator\AuthenticatorApi;
use model\authenticator\AuthenticatorWeb;
use model\container\ApiContainer;
use model\container\WebContainer;
use model\datasource\DBPdo;
use model\dependencyManager\HttpDependencyManager;
use model\helper\TemplateParser;
use model\response\ApiResponseObject;
use model\response\WebResponseObject;

class DependencyManagerFactory {

    public static function createHttpDependencyManager(ConfigInterface $frameworkConfig, ConfigInterface $appConfig, HTTPManagerInterface $requestObject, FileParseInterface $fileReader) {

        $requestType = $requestObject->getAppRequestType();

        $dependencyManager = new HttpDependencyManager($frameworkConfig,$appConfig,$fileReader);
        $dependencyManager->addInstantiatedDependency('config',$appConfig);
        $dependencyManager->addInstantiatedDependency('frameworkConfig',$frameworkConfig);
        $dependencyManager->addInstantiatedDependency('dependencyManager',$dependencyManager);
        $dependencyManager->addInstantiatedDependency('request',$requestObject);
        //add a way to check for the datasource from the config

        $dependencyManager->addInstantiatedDependency('datasource',  new DBPdo($appConfig->getDataSourceDetails()));

        switch($requestType) {
            case RequestTypeEnum::WEB:
                    $dependencyManager->addInstantiatedDependency('authenticator',  new AuthenticatorWeb(...$dependencyManager->getDependencies('model\authenticator\AuthenticatorWeb')));
                $dependencyManager->addInstantiatedDependency('response',  new WebResponseObject(new TemplateParser('[[',']]',$appConfig->getDefaultTitle())));

                $dependencyManager->addInstantiatedDependency('container',new WebContainer(...$dependencyManager->getDependencies('model\container\WebContainer')));
                break;
            case RequestTypeEnum::API:

                $dependencyManager->addInstantiatedDependency('authenticator',  new AuthenticatorApi(...$dependencyManager->getDependencies('model\authenticator\AuthenticatorApi')));
                $dependencyManager->addInstantiatedDependency('response',  new ApiResponseObject());
                $dependencyManager->addInstantiatedDependency('container',new ApiContainer(...$dependencyManager->getDependencies('model\container\ApiContainer')));
                break;
        }

        return $dependencyManager;

    }

}
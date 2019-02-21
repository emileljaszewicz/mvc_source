<?php
require "vendor/autoload.php";

use library\SessionManager;
use library\UrlManager;

session_start();

if(empty($_SESSION['userClass'])) {
    $userEntityObject = new \library\EntityObjectCreator(new \library\QueryBuilder());
    $sessionManager = new SessionManager();
    $sessionManager->addSessionData('userClass', $userEntityObject);
}

$requestManager = new \library\RequestManager();
if(($requestManager->getGetMethodResult("task") !== null) && ($requestManager->getGetMethodResult("action") !== null)){

//    $controllerClass = "\\controller\\".$requestManager->getGetMethodResult('task').'Controller';
//    $method = $requestManager->getGetMethodResult('action');
//    $ob = new $controllerClass();
//    $ob->$method();

    $requestManager->getRequestedRoute();
}
else{
    $requestManager->redirectToPermitedRequest();
}



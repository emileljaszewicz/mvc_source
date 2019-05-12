<?php
require "vendor/autoload.php";

use library\SessionManager;

session_start();

$requestManager = new \library\RequestManager();
if(($requestManager->getGetMethodResult("task") !== null) && ($requestManager->getGetMethodResult("action") !== null)){
    $requestManager->getRequestedRoute();
}
else{
    $requestManager->redirectToPermitedRequest();
}



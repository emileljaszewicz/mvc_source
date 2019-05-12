<?php
namespace library;


class RequestManager extends UrlManager
{
    private $annotationManager;
    private $sessionManager;
    public function __construct()
    {
        parent::__construct();
        $this->annotationManager = new AnnotationManager();
        $this->sessionManager = new SessionManager();
        $this->sessionManager->saveUrlRequests($this->generateUrl());
    }
    public function redirectToUrl($urlPath){
        return header("location:".$urlPath);
    }
    public function getRequestedRoute(){
        $controller = "\\controller\\".$this->getGetMethodResult('task').'Controller';
        $controllerResponseResults = $this->annotationManager->returnAnnotationResponseData($controller, 'controllerAnnotations');
        if($controllerResponseResults !== false) {
            if (array_key_exists('controller', $controllerResponseResults) && (array_key_exists('controllersMethod', $controllerResponseResults))) {
                $method = $this->getGetMethodResult('action');
                $controllersMethodResponseResults = $this->annotationManager->returnAnnotationResponseData($controller, $method);

                if ($controllersMethodResponseResults !== false) {
                    if (is_array($controllersMethodResponseResults) && (array_key_exists('controller', $controllersMethodResponseResults) && (array_key_exists('controllersMethod', $controllersMethodResponseResults)))) {
                        $controllerOb = new $controller();

                        return $controllerOb->$method();
                    } else {

                        return $controllersMethodResponseResults;
                    }
                }
            }
        }
        echo "You don't have access permissions to this route";
    }
    public function redirectToPermitedRequest(){
        $dirControllers = $this->annotationManager->getAllControllerClassObjects();
        foreach ($dirControllers as $controller){
            $controllerResponseResults = $this->annotationManager->returnAnnotationResponseData($controller, 'controllerAnnotations');

            if($controllerResponseResults !== false) {
                if (array_key_exists('controller', $controllerResponseResults) && (array_key_exists('controllersMethod', $controllerResponseResults))) {
                    $controllerMethods = $this->annotationManager->getControllerMethods($controller);
                    foreach ($controllerMethods as $controllerMethod) {
                        $controllersMethodResponseResults = $this->annotationManager->returnAnnotationResponseData($controller, $controllerMethod);
                        if ($controllersMethodResponseResults !== false) {

                            if (is_array($controllersMethodResponseResults) && (array_key_exists('controller', $controllersMethodResponseResults) && (array_key_exists('controllersMethod', $controllersMethodResponseResults)))) {
                                $task = explode('\\controller\\',explode('Controller', $controller)[0])[1];
                                return header('location:index.php?task=' . $task . '&action=' . $controllerMethod);

                                exit();
                            } else {

                                echo 'Returned method annotations can return only boolean type, necessary to redirect to route path in: ' . $controller . '::' . $controllerMethod;
                                exit();
                            }
                        }
                    }
                }
            }
        }
        echo 'Request manager not found a permitted route to return';
    }

    public function getPreviousRequest(){
        $requests = $this->sessionManager->getUrlRequests();
        $requestsCount = count($requests);
        if($requestsCount > 1) {
            return $requests[$requestsCount - 2];
        }
        else{
            return $requests[$requestsCount - 1];
        }
    }
    public function getPostData($postIndexName){

        if(isset($_POST[$postIndexName])){
            return $_POST[$postIndexName];
        }
        else{
            return null;
        }
    }
}
<?php
namespace library;

use processor\AnnotationsProcessor;
use ReflectionClass;


class AnnotationManager
{
    private $urlManager = null;
    public function __construct()
    {
        $this->urlManager = new UrlManager();

    }

    public function getAllControllerClassObjects(){
        $controllersArray = [];
        $dirControllers = $this->urlManager->getDirFiles('controller/', UrlManager::FILE);

        foreach ($dirControllers as $controller){

                $controllerObject =  "\\controller\\".explode('.php', $controller)[0];
                $reflection = new ReflectionClass($controllerObject);
                if((!$reflection->isAbstract()) && (!$reflection->isInterface())) {
                    $controllersArray[] = $controllerObject;
                }

        }
        return $controllersArray;
    }
    public function getControllerMethods($object){
        return get_class_methods($object);
    }

    private function getAnnotationsFromCommentString($commentString){
        //define the regular expression pattern to use for string matching
        $pattern = "#(@[a-zA-Z]+\s*[a-zA-Z0-9,()_].*)#";
        //perform the regular expression on the string provided
        preg_match_all($pattern, $commentString, $matches, PREG_PATTERN_ORDER);

        return $matches[0];
    }
    public function getRequestPathAnnotationResult($requestController, $requestAction){
        $reflectionClass = new ReflectionClass(new $requestController);
        if($requestAction == 'controllerAnnotations'){
            $comment_string = $reflectionClass->getDocComment();

        }
        else {
            $comment_string = $reflectionClass->getMethod($requestAction)->getdoccomment();
        }
        $requestAnnotations = $this->getAnnotationsFromCommentString($comment_string);



        return $this->getRequestAnnotationsMethodResult($requestAnnotations, explode('Controller', explode('\\', $requestController)[1])[0], $requestAction);
    }
    public function getRequestAnnotationsMethodResult($requestAnnotations, $controllerName, $actionName){

        if(count($requestAnnotations) > 0) {
            $annotationObject = null;
            $arr = [];
            foreach ($requestAnnotations as $requestAnnotation) {

                $methodAnnotationObjectName = "\\annotations\\" . explode('@', trim($requestAnnotation))[1];
                $className = strstr($methodAnnotationObjectName, '(', true);
                $classArgs = strstr($methodAnnotationObjectName, '(');
                $classMethod = substr($classArgs, 1, -1);
                $a = strstr($classMethod, '(', true);
                $b = substr(strstr($classMethod, '('), 1, -1);
                $ob = new $className(['controllerName' => $controllerName, 'methodName' => $actionName]);
                if (method_exists($ob, $a)) {
                    $arr[] = $ob->$a($b);

                } else {
                    continue;
                }
            }

            return $this->convertAnnotationResultsArray($arr);
        }
        else{
            return ['annotations' => null];
        }
    }

    public function returnAnnotationResponseData($controller, $method){

        $getProcessedAnnotations = $this->getRequestPathAnnotationResult($controller, $method);

//        $processedAnnotationController = new $controller();
       $annotationsProcessor = new AnnotationsProcessor($getProcessedAnnotations, $controller, $method);

       return $annotationsProcessor->processAnnotationResults();
    }
    private function convertAnnotationResultsArray(array $annotationResults){
        $convertedArray = [];
        foreach ($annotationResults as $annotationResult){
            foreach ($annotationResult as $resultKey => $resutValue){
                $convertedArray[$resultKey] = $resutValue;
            }
        }

        return $convertedArray;
    }

}
<?php
namespace processor;


use controller\Controller;
use library\Interfaces\AnnotationInterface;

class AnnotationsProcessor
{
    private $annotationsResults = null;
    private $controller = null;
    private $controllersMethod = null;
    public function __construct(array $processedMethodAnnotationsRsults, $controller, $controllersMethod)
    {

        $this->annotationsResults = $processedMethodAnnotationsRsults;
        $this->controller = $controller;
        $this->controllersMethod = $controllersMethod;
    }
    public function processAnnotationResults(){
        $data = [];
        foreach ($this->annotationsResults as $annotationsAction => $annotationsData){

            $annotationResponse = $this->operateOnMethodsAnnotation($annotationsAction, $annotationsData);
            $data[] = $annotationResponse;
        }
        return $this->accumulateResponseResults($data);
    }
    private function accumulateResponseResults(array $responseData){
        foreach($responseData as $annotationData){
            if($annotationData['response'] === false){

                return false;
            }
            if(!is_bool($annotationData['response']) && !is_null($annotationData['response'])){
                return $annotationData['response'];
            }
        }
       return ['controller' => $this->controller, 'controllersMethod' => $this->controllersMethod];
    }
    private function operateOnMethodsAnnotation($annotationsAction, $annotationsData){
        switch ($annotationsAction){
            case 'access':
                if($this->annotationsResults[$annotationsAction] == true){
                    return ['response' => true];
                }
                else{
                    return ['response' => false];
                }
                break;
            case 'print':
                return ['response' => $annotationsData];
                break;
            case 'skipInSearch':
                if($this->annotationsResults[$annotationsAction] == true){
                    return ['response' => false];
                }
                else{
                    return ['response' => true];
                }
                break;
        }
    }
}
<?php
namespace annotations;


use library\Interfaces\AnnotationInterface;

class Others implements AnnotationInterface
{


    public function __construct($controllerParameters = null)
    {
        parent::__construct($controllerParameters);
    }
    public function some($string){
        return ['print' => $this->printData()];
    }
    public function printData(){


        return $this;
    }

}
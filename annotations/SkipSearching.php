<?php
/**
 * Created by PhpStorm.
 * User: E
 * Date: 23.12.2018
 * Time: 18:57
 */

namespace annotations;


use library\Interfaces\AnnotationInterface;
use library\UrlManager;

class SkipSearching implements AnnotationInterface
{

    private  $d;
    public function __construct($controllerParameters = null)
    {


    }

    public function skip($stringData){

        $urlManager = new UrlManager();
        if(($stringData == 'true') && ($urlManager->getGetMethodResult('task') == null) && ($urlManager->getGetMethodResult('action') == null)){
            return ['skipInSearch' => true];
        }
        else{
            return ['skipInSearch' => false];
        }
    }
}
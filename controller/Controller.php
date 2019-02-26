<?php
namespace controller;

use library\QueryBuilder;
use library\ClassAutoInitializer;
use library\RequestManager;
use library\SessionManager;
use library\ViewManager;

abstract class Controller extends ViewManager
{
    protected function loadmodel($name, $path = 'model/'){
        $path = $path.$name.'Model.php';
        $modelclassName = $name.'Model';

        require $path;
        $ob = new $modelclassName();

        return $ob;
    }
    protected function render($name, $data = null){
        $this->generateHeader("header");
        $this->appendBody($name, $data);
        $this->generateBottom("bottom");

        foreach ($this->getMergedData() as $html){
            include($html);
        }

    }
    protected function queryBuilder (){
        $queryBuilder = new QueryBuilder();

        return $queryBuilder;
    }
    protected function getHelper($helperKey){
        $helper = new ClassAutoInitializer();

        return $helper->getInitializedObject($helperKey);
    }
    protected function getSessionManager(){
        $sessionManager = new SessionManager();

        return $sessionManager;
    }
    protected function getRequestManager(){
        $requestManager = new RequestManager();

        return $requestManager;
    }
}
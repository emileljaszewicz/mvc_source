<?php
namespace controller;

use library\QueryBuilder;
use library\ClassAutoInitializer;
use library\RequestManager;
use library\SessionManager;

abstract class Controller
{
    protected function loadmodel($name, $path = 'model/'){
        $path = $path.$name.'Model.php';
        $modelclassName = $name.'Model';

        require $path;
        $ob = new $modelclassName();

        return $ob;
    }
    protected function render($name, $data = null){
        if(!is_array($data) && ($data != null)){
            throw new Exception("Second parameter must be an array ".gettype($data)." given");
        }
        if(!is_file('templates/'.$name.'.html.php')) {
            throw new Exception("No such file in templates directory!!");
        }

        include('templates/' . $name . '.html.php');
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
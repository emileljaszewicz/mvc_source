<?php
namespace controller;

use library\QueryBuilder;
use library\ClassAutoInitializer;
use library\RequestManager;
use library\SessionManager;
use library\UsersCreator;
use library\ViewManager;
use Rakit\Validation\Validator;

abstract class Controller extends ViewManager
{
    protected $postData;
    public function __construct()
    {
        $this->postData = $_POST;
    }

    public function getMessages($messageType){
        $sessionManager = $this->getSessionManager();
        $message = $sessionManager->getSessionData($messageType);

        $sessionManager->remove($messageType);
        if(($message !== null)) {

            return implode(PHP_EOL, $message);
        }
        else{
            return null;
        }
    }
    protected function setMessage($messageType, $essageContent){
        $errors = [];
        $sessionManager = $this->getSessionManager();
        $errors[$messageType][] = $essageContent;
        $sessionManager->addSessionData($messageType, $errors[$messageType]);
    }
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

    protected function redirect($redirectPath){
        $RequestManager = $this->getRequestManager();

        return $RequestManager->redirectToUrl($redirectPath);
    }

    protected function getUser(){
        $sessionManager = $this->getSessionManager();
        $usersCreator = new UsersCreator();
        $usersCreator->findUser(['userId' => $sessionManager->getSessionData('userId')]);

        return $usersCreator;
    }
    protected function getValidator($customMessages = []){
        return new Validator($customMessages);
    }
}
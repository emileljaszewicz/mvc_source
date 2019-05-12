<?php
namespace annotations;

use library\Interfaces\AnnotationInterface;
use library\QueryBuilder;
use library\SessionManager;
use library\Typer;
use library\UrlManager;
use library\UsersCreator;
use userranks\UserRank;

class Privileges implements AnnotationInterface
{

    private $userranks;
    private $cp;
    public function __construct($controllerParameters = null)
    {
        $this->cp = $controllerParameters;
    }
    public function isUnlogged($sessionData){
        $sessionManager = new SessionManager();
        if(empty($sessionManager->getSessionData('userId'))){
            return ["access" => true];
        }
        else{
            return ["access" => false];
        }
    }
    public function isLogged(){
        $sessionManager = new SessionManager();
        if(empty($sessionManager->getSessionData('userId'))){
            return ["access" => false];
        }
        else{
            return ["access" => true];
        }
    }
    public function grantedUser($ObjectPath){
        $rankObject = new $ObjectPath();
        $sessionManager = new SessionManager();
        $usersCreator = new UsersCreator();
        $usersCreator->findUser(['userId' => $sessionManager->getSessionData('userId')]);

        //var_dump($sessionManager->getSessionData('userClass')->getUserRankObject());
        if($usersCreator->getUserRankObject() instanceof $rankObject){

            return ["access" => true];
        }
        else{
            return ["access" => false];
        }

    }
    public function isMethod($method){
        $methodData = null;
        switch ($method){
            case 'POST':
                $methodData = $_POST;
                break;
        }

        if(!empty($methodData)){
            return ["access" => true];
        }
        else{
            return ["access" => false];
        }
    }
    public function printData(){

        return ['print' => $this->ss('asss!!####')];
    }
    public function ss($zm){
echo 'sss';

    }
}
<?php
namespace annotations;

use library\Interfaces\AnnotationInterface;
use library\QueryBuilder;
use library\SessionManager;
use library\Typer;
use library\UrlManager;
use userranks\UserRank;

class Privileges implements AnnotationInterface
{

    private $userranks;
    private $cp;
    public function __construct($controllerParameters = null)
    {
        $this->cp = $controllerParameters;
    }
    public function grantedUser($ObjectPath){
        $rankObject = new $ObjectPath();

        $sessionManager = new SessionManager();
        //var_dump($sessionManager->getSessionData('userClass')->getUserRankObject());
        if($sessionManager->getSessionData('userClass')->getUserRankObject() instanceof $rankObject){
            return ["access" => true];
        }
        else{
            return ["access" => false];
        }

    }
    public function saySomething($string){
        return $string;
    }
    public function printData(){

        return ['print' => $this->ss('asss!!####')];
    }
    public function ss($zm){
echo 'sss';

    }
}
<?php
namespace library;


class SessionManager
{

    private $urlRequests;
    public function addSessionData($key, $value){
        $_SESSION[$key] = serialize($value);

        return $_SESSION;
    }
    public function getSessionData($key){
        if(isset($_SESSION[$key])) {
            return unserialize($_SESSION[$key]);
        }
        else{
            return null;
        }
    }

    public function saveUrlRequests($urlString){

        if(!isset($_SESSION['urlRequests'])) {
            $_SESSION['urlRequests'] = [];
            $urlCount = 0;
        }
        else{
            $urlCount = count($_SESSION['urlRequests']);
        }
        if(!in_array($urlString, $_SESSION['urlRequests']) && ($urlString != null)) {
            $_SESSION['urlRequests'][$urlCount] = $urlString;
        }
        array_reverse($_SESSION['urlRequests']);
        array_splice($_SESSION['urlRequests'], 5);
        array_reverse($_SESSION['urlRequests']);

    }

    public function getUrlRequests(){
        if(isset($_SESSION['urlRequests'])) {
            return $_SESSION['urlRequests'];
        }
        else{
            return null;
        }
    }
}
<?php
namespace library;

use ReflectionClass;
use ReflectionMethod;

class UrlManager
{
    const FILE = 'is_file';
    const DIRECTORY = 'is_dir';
    private $urlPath = [];
    private $HTTP_GET_VARS;
    public function __construct()
    {
        $this->HTTP_GET_VARS = $_GET;
    }

    /**
     * Check path to controllers method defined in url
     * returns url path only for public method property
     * @return array
     */
    public function getMethodUrlPathArray(){

        $getAllKeys = array_keys($this->HTTP_GET_VARS);
        $dirControllers = $this->getDirFiles('controller/', self::FILE);
        foreach ($getAllKeys as $key) {
            if($key == 'task'){

                foreach ($dirControllers as $controller){

                    if($this->HTTP_GET_VARS[$key].'Controller.php' == $controller){

                        $controllerObject =  "\\controller\\".explode('.php', $controller)[0];
                        $reflection = new ReflectionClass($controllerObject);
                        if((!$reflection->isAbstract()) && (!$reflection->isInterface())) {

                            $this->urlPath['task'] = new $controllerObject();
                        }
                    }
                }

            }
            else if($key == 'action'){

                if(array_key_exists('task', $this->urlPath)){

                    $reflection = (method_exists($this->urlPath['task'], $this->HTTP_GET_VARS[$key]))? new ReflectionMethod($this->urlPath['task'], $this->HTTP_GET_VARS[$key]): null;

                    if(is_object($this->urlPath['task']) && method_exists(get_class($this->urlPath['task']), $this->HTTP_GET_VARS[$key]) && ($reflection->isPublic())){
                         $this->urlPath['task'] = $this->HTTP_GET_VARS['task'];
                        $this->urlPath['action'] = $this->HTTP_GET_VARS['action'];
                    }
                    else{
                        $this->urlPath['task'] = null;
                        $this->urlPath['action'] = null;
                    }
                }
            }
            else{
                $this->urlPath['args'][$key] = (!empty($this->HTTP_GET_VARS[$key]))?$this->HTTP_GET_VARS[$key]: null;
            }
        }
        return $this->urlPath;
    }

    /**
     * Creates URL path from existing array from method getMethodUrlPathArray
     * @return string
     */
    public function generateUrl(){
        if(($this->getGetMethodResult('task') != null) && ($this->getGetMethodResult('action') != null)) {
            $urlFromArray = [];

            foreach ($this->getMethodUrlPathArray() as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $argsKey => $argsValue) {
                        $urlFromArray[] = $argsKey . '=' . $argsValue;
                    }
                } else {
                    $urlFromArray[] = $key . '=' . $value;
                }
            }
            return implode('&', $urlFromArray);
        }
        else{
            return null;
        }
    }

    /**
     * Returns value of inserted index from URL
     * If url GET method index exists, returns value
     * @param $getIndex
     * @return mixed|null
     */
    public function getGetMethodResult($getIndex){

        $mergedArray = [];
        foreach($this->getMethodUrlPathArray() as $key => $value){

            if(is_array($value)){
                foreach ($value as $argsKey => $argsValue){
                    $mergedArray[$argsKey] = $argsValue;
                }
            }
            else{
                $mergedArray[$key] = $value;
            }
        }
        return (array_key_exists($getIndex, $mergedArray))? $mergedArray[$getIndex]: null;
    }

    public function getSessionId($getIndex){
        if(!empty($_SESSION[$getIndex])){
            return $_SESSION[$getIndex];
        }
        else{
            return null;
        }
    }
    /**
     * Returns all directory path elements
     * If passed class const, returns directories or files
     * @param $path
     * @param null $constSelector
     * @return array
     */
    public function getDirFiles($path, $constSelector = null){
        $directoryContent = scandir($path);
        $contentCollection = [];
        foreach($directoryContent as $dirElement){
            if($this->checkFileSelector($path.$dirElement, $constSelector)){
                $contentCollection[] = $dirElement;
            }
        }
        return $contentCollection;
    }
    private function checkFileSelector($file, $selector){

        switch ($selector){
            case 'is_file':
                return is_file($file);
                break;
            case 'is_dir':
                return is_dir($file);
                break;
            default:
                return null;
                break;
        }
    }

}
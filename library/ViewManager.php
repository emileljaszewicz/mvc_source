<?php
namespace library;


class ViewManager
{
    private $headerData  = [];
    private $headerScripts = [];
    private $bodyContent = [];
    private $bottomData = [];
    private $pageTitle;

    public function setPageTitle($pageTitle){

        $this->pageTitle = $pageTitle;
    }
    public function getPageTitle(){

        return $this->pageTitle;
    }
    public function generateHeader($headerTemplate, $headerPath = 'templates/'){
        $this->catchErrorTemplate($headerTemplate);
        $this->headerData[] = $headerPath.$headerTemplate . '.html.php';
        return $this->headerData;
    }
    public function appendBody($bodyTemplate, $data = null, $headerPath = 'templates/'){
        $this->catchErrorTemplate($bodyTemplate, $data);
        $this->bodyContent[] = $headerPath.$bodyTemplate . '.html.php';
        return $this->bodyContent;
    }
    public function generateBottom($bottomTemplate, $headerPath = 'templates/'){
        $this->catchErrorTemplate($bottomTemplate);
        $this->bottomData[] = $headerPath.$bottomTemplate . '.html.php';
        return $this->bottomData;
    }
    public function printHeaderScripts(){
        return implode(PHP_EOL, $this->headerScripts).PHP_EOL;
    }
    protected function appendHeaderScripts($data = []){
        if(array_key_exists('styles', $data)){
            foreach($data['styles'] as $dataStylePath){
                $this->headerScripts[] = '<link rel="stylesheet" type="text/css" href="'.$dataStylePath.'">';
            }
        }
        if(array_key_exists('scripts', $data)){
            foreach($data['scripts'] as $dataScriptPath){
                $this->headerScripts[] = '<script src="'.$dataScriptPath.'" ></script>';
            }
        }
    }
    protected function getMergedData(){

        return array_merge($this->headerData, $this->bodyContent, $this->bottomData);
    }
    protected function catchErrorTemplate($name, $data = null){
        if(!is_array($data) && ($data != null)){
            throw new \Exception("Second parameter must be an array ".gettype($data)." given");
        }
        if(!is_file('templates/'.$name.'.html.php')) {
            throw new \Exception("No such file in templates directory!!");
        }
    }
}
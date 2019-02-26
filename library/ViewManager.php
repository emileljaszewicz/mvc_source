<?php
namespace library;


class ViewManager
{
    private $headerData  = [];
    private $bodyContent = [];
    private $bottomData = [];

    public function createHtmlView(){

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
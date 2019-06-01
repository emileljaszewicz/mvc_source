<?php
namespace library;


class HTTPMethodHandlerFilter
{
    private $mergedArrayData;
    private $arrayDataReturn;

    public function setMethodsData($httpMethodsData){
        if(is_array($httpMethodsData)) {
            $this->mergedArrayData = array_merge($httpMethodsData);
        }
        else{
            $this->mergedArrayData = $httpMethodsData;
        }

        return $this;
    }
    public function getData($methodIndexName){

        return (new HTTPMethodHandlerFilter())->setMethodsData((array_key_exists($methodIndexName, $this->mergedArrayData)? $this->mergedArrayData[$methodIndexName]: ""));
    }

    public function getValues(){
        return $this->mergedArrayData;
    }
}
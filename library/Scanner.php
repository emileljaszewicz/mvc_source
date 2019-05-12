<?php
namespace library;


class Scanner
{
    private $constSearchResults;
    private $directoryPath;
    private $scanArrayResult = [];

    const CLASS_INSTANCE = "CLASS_INSTANCE";
    const FILE = 'FILE';
    const CLASS_METHOD = 'METHOD';

    public function scanDirectory($directoryPath){
        $this->directoryPath = $directoryPath;
    }
    public function searchFor($CONSTANT_VALUE, $searchData){
        $this->constSearchResults = ["constant" => $CONSTANT_VALUE, "search" => $searchData];
    }
    public function startScan(){
        return $this->scan($this->directoryPath);
    }

    private function getSearchFor(){
        return $this->constSearchResults;
    }
    private function scan($directorypath){
        $directories = scandir($directorypath);

        foreach ($directories as $directory){

            if(is_dir($directorypath.'/'.$directory) && !in_array($directory, ['.','..'])) {
                //echo $directorypath.'/'.$directory . '<br>';
                $this->scan($directorypath.'/'.$directory);
            }
            else if(is_file($directorypath.'/'.$directory)){
                $this->scanArrayResult[$directorypath]['dirFiles'][] = $directory;
                $this->scanArrayResult[$directorypath]['additional'][] = $this->searchConditions($this->getSearchFor()["constant"], $this->getSearchFor()["search"], $directorypath.'/'.$directory);
            }
        }
        return $this->scanArrayResult;
    }
    private function hasDuplicates(array $assocArray){
        $arr = [];
        foreach ($assocArray as $assoc){
            foreach($assoc as $key => $value){
                if(!in_array($value, array_values($arr[$key]))){
                    $arr[$key] = $value;
                }
                else{
                    return true;
                }
            }
        }
        return false;
    }
    private function searchConditions($CONSTANT_VALUE, $searchData, $directoryfile){
        switch ($CONSTANT_VALUE){
            case 'CLASS_INSTANCE':
                if(explode('.',$directoryfile)[1] == 'php') {
                    $classNameSpace = str_replace('/', '\\', explode('.', $directoryfile)[0]);
                    $objectPath = "\\" . $classNameSpace;
                    $classReflection = new \ReflectionClass($objectPath);

                    if($classReflection->getParentClass() !== false && $classReflection->getParentClass()->getName() == $searchData){
                        return ["found" => $objectPath];
                    }
                }

        }

        return null;
    }
}


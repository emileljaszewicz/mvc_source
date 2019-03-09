<?php
namespace library;




class DBEntity
{
    private $rowId;
    private $arrayValues;

    public function __construct($rowId = null)
    {

        if(!empty($rowId) && !is_array($rowId)) {
            throw new \Exception("Value idData must be an array");
        }
        $this->rowId = $rowId;
        foreach ($this->getEntityData($this->rowId)->getPdoArray() as $iterate => $obParams){
            foreach ($obParams as $entityField => $value){
                foreach ($this->getClassProperties() as $fieldName => $methodName){
                    if(strtolower($fieldName) == strtolower($entityField)){
                        if(!is_array($this->rowId)){
                            $this->arrayValues[$entityField][] = $value;
                        }
                        else{
                            $this->arrayValues[$entityField] = $value;
                        }

                        $setMethod = 'set'.$methodName;
                        $this->$setMethod($this->arrayValues[$entityField]);
                    }

                }
            }
        }
    }
    private function getEntityName(){

        $classNameToArray = explode('\\',get_class($this));
        $className = $classNameToArray[sizeof($classNameToArray)-1];

        return $className;
    }

    public function getEntityData($params = null){
        $queryBuilder = new QueryBuilder();
        $queryBuilder->createQueryForTable($this->getEntityName());

        $queryBuilder->selectData();
        if(!empty($this->rowId)){
            $params = $this->rowId;
        }
        if(is_array($params) && sizeof($params) > 0){

            $queryBuilder->where($params);
        }

        return new EntityObjectCreator($queryBuilder);

    }
    private function getClassProperties(){

        $classMethods = array_filter(get_class_methods($this), function($classMethod){
            return strstr($classMethod, "set");
        });

        $reflect = new \ReflectionClass($this);
        $properties = $reflect->getProperties(\ReflectionProperty::IS_PRIVATE);
        $propertiesArray = [];


        foreach ($properties as $classProperty){
            $propertyName = strtolower(str_replace("_", "", $classProperty->getName()));
            foreach ($classMethods as $classMethod){
                if('set'.$propertyName == strtolower($classMethod)){
                    $propertiesArray[$classProperty->getName()] = str_replace('set', '', $classMethod);
                }
            }

        }

        return $propertiesArray;
    }
    public function joinEntityData(EntityObjectCreator $entityData){
        $ob = $this->getEntityData();
        $ob->joinEntityCollumns($entityData);
        return $ob;
    }
    public function save(){
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->createQueryForTable($this->getEntityName());

        $classProperties = $this->getClassProperties();

        $queryBuilder->createQueryForTable($this->getEntityName());

        foreach ($classProperties as $entityField => $value){
            $methodName = 'get'.$value;
            if(!is_array($this->$methodName())) {
                $queryBuilder->prepareData($entityField, $this->$methodName());
            }

        }
        if($this->rowId === null){
           $queryBuilder->insertData();
        }
        else{
            $queryBuilder->updateData();
            $queryBuilder->where($this->rowId);
        }

        return $queryBuilder->execQuery();
    }
    private function getQueryBuilder(){
        $queryBuilderObject = new QueryBuilder();

        return clone $queryBuilderObject;
    }
}
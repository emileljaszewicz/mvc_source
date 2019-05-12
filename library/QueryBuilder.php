<?php
namespace library;


use database\MySQLConnect;

class QueryBuilder
{
    private $mysqlData = [];
    public $tableName;
    private $query;
    private $pdo;
    private $queryBindValues = [];
    public function __construct()
    {
        $pdoob = new MySQLConnect();
        $this->pdo = $pdoob->connect();
    }

    public function createQueryForTable($tableName){
        $this->tableName = $tableName;
        $this->query .= $this->tableName;

        return $this->query;
    }
    public function prepareData($tableField, $dataToExecute){
        foreach ($this->mysqlData as $field => $data){
            if(array_key_exists($tableField, $this->mysqlData)){
                throw new \Exception("Fields name $tableField already exists in query statement");
            }
        }
        $this->mysqlData[$tableField] = $dataToExecute;

        return $this->mysqlData;
    }
    public function insertData(){
        $tablefields =  [];
        $values = [];
        foreach ($this->mysqlData as $field => $data){
            $tablefields[] = $field;
            $values[] = ":$field";
            $this->queryBindValues[":$field"] = $data;
        }
        $this->query = "Insert Into ".strtolower($this->tableName)." (".implode(',', $tablefields).") Values(".implode(',', $values).");";

        return $this->query;
    }
    public function updateData(){
        $queryfields =  [];
        foreach ($this->mysqlData as $field => $data){
            $queryfields[] = "$field = :".$field;
            $this->queryBindValues[":$field"] = $data;
        }
        $this->query = "Update $this->tableName Set ".implode(', ', $queryfields);
        return $this->query;
    }
    public function removeData(){
        $removeColls = [];
        $removeFields = [];
        foreach ($this->mysqlData as $field => $data){
            if(!in_array($field, $removeColls)) {
                $removeColls[] = "$field = :$field";
               // $removeFields[] = ":$field";
                $this->queryBindValues[":$field"] = $data;
            }
        }

        //$this->query = "DELETE FROM $this->tableName WHERE (".implode(",", $removeColls).") IN (".implode(",", $removeFields).");";
        $this->query = "DELETE FROM $this->tableName WHERE ".implode(' AND ', $removeColls);

        return $this->query;
    }
    public function where(array $fieldsToCompare){
        $queryfields =  [];

        foreach($fieldsToCompare as $field => $data){
            if(is_int($field)){
                throw new \Exception("Bad array arguments. Mysql field name must be a string !!");
            }
            //$queryfields[] = "$field = ".$this->addQuotes($data);
            $queryfields[] = "$field = :$field";
            $this->queryBindValues[":$field"] = $data;
        }

        $this->query .= " Where ".implode(' And ', $queryfields);
        return $this->query;
    }
    public function selectData($tableFields = '*'){
        if(is_array($tableFields)){
            $tableFields = implode(',', $tableFields);
        }
        $this->query = "Select ".$tableFields.' From '.$this->tableName;
        return $this->query;
    }
    public function execQuery(){
        try {
            $q = str_replace('\\', '\\\\', $this->query) . ';';

            $dataToSent = $this->pdo->prepare($q);

            foreach ($this->queryBindValues as $valueName => $valueToExecute){
                $dataToSent->bindValue($valueName, $valueToExecute);
            }

            if(!$dataToSent->execute()){
                throw new \Exception($dataToSent->errorInfo()[2]);
            }

            return $dataToSent;
        }
        catch (\PDOException $exception){
            echo $exception->getMessage();
        }
    }
    public function getTableKeyData($keyName){
        $this->query = "SHOW KEYS FROM {$this->tableName} WHERE Key_name = '$keyName'";

        return $this->execQuery()->fetchAll();
    }
    private function addQuotes($dataToInsert){
        $getdatatype = gettype($dataToInsert);
        $return = null;
        switch($getdatatype){
            case 'NULL':
                $return = gettype( null);
                break;
            case 'integer':
                $return = $dataToInsert;
                break;
            default:
                $return = "'".$dataToInsert."'";
        }
        return $return;
    }
}
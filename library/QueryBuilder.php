<?php
namespace library;


use database\MySQLConnect;

class QueryBuilder
{
    private $mysqlData = [];
    public $tableName;
    private $query;
    private $pdo;
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
    public function prepareData($tableField, $dataToSave){
        foreach ($this->mysqlData as $field => $data){
            if(array_key_exists($tableField, $this->mysqlData)){
                throw new \Exception("Fields name $tableField already exists in query statement");
            }
        }
        $this->mysqlData[$tableField] = $dataToSave;

        return $this->mysqlData;
    }
    public function insertData(){
        $tablefields =  [];
        $values = [];
        foreach ($this->mysqlData as $field => $data){
            $tablefields[] = $field;
            $values[] = $this->addQuotes($data);
        }
        $this->query = "Insert Into $this->tableName (".implode(',', $tablefields).") Values(".implode(',', $values).");";

        return $this->query;
    }
    public function where(array $fieldsToCompare){
        $queryfields =  [];

        foreach ($this->mysqlData as $field => $data){
            $queryfields['fields'][] = "$field = ".$this->addQuotes($data);
        }
        foreach($fieldsToCompare as $field => $data){
            if(is_int($field)){
                throw new \Exception("Bad array arguments. Mysql field name must be a string !!");
            }
            $queryfields[] = "$field = ".$this->addQuotes($data);
        }
        $this->query .= " Where ".implode(' And ', $queryfields);
        return $this->query;
    }
    public function updateSet(array $fieldsToCompare){
        $queryfields =  [];
        foreach ($fieldsToCompare as $field => $data){
            $queryfields[] = "$field = ".$this->addQuotes($data);
        }
        $this->query = "Update $this->tableName Set ".implode(', ', $queryfields);
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
        $q = $this->query.';';
        $dataToSent = $this->pdo->prepare($q);
        $dataToSent->execute();

        return $dataToSent;
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
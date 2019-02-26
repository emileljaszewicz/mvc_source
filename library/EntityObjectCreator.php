<?php
namespace library;


class EntityObjectCreator
{
    private $joinedArray = [];
    private  $dataarray = [];
    private $pdoQuery;
    public function __construct(QueryBuilder $entityTable)
    {
        $this->pdoQuery = $entityTable->execQuery()->fetchAll(\PDO::FETCH_ASSOC);

    }
    function __call($func, $params){
        foreach ($this->pdoQuery as $queryField) {
            if (in_array($func, array_keys($queryField))) {
                $this->dataarray[][$func] = $queryField[$func];
            }
        }
        if(count($this->dataarray) == 1){
            $getRecordKey = array_keys($this->dataarray[0]);
            return $this->dataarray[0][$getRecordKey[0]];
        }
        return $this->dataarray;
    }

    public function getPdoArray(){

        return $this->pdoQuery;
    }
    public function joinEntityCollumns(EntityObjectCreator $entityTable){

        foreach ($this->getPdoArray() as $iteratedKey2 => $iteratedJoinValue2){
            foreach ($this->getPdoArray()[$iteratedKey2] as $toJoinKey => $toJoinValue){
                $this->joinedArray[$iteratedKey2][$toJoinKey] = $toJoinValue;
                foreach ($entityTable->getPdoArray() as $iteratedKey1 => $iteratedJoinValue) {
                    foreach ($entityTable->getPdoArray()[$iteratedKey1] as $joinedKey => $joinedValue) {
                        if (array_key_exists($toJoinKey, $iteratedJoinValue) && ($iteratedJoinValue[$toJoinKey] == $toJoinValue)) {
                            unset($this->joinedArray[$iteratedKey2][$toJoinKey]);
                            $this->joinedArray[$iteratedKey2][$joinedKey] = $joinedValue;
                        }
                    }
                }

            }

        }
        return $this->joinedArray;
    }
    public function getJoinedArray(){
        return $this->joinedArray;
    }
}
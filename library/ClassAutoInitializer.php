<?php
namespace library;


class ClassAutoInitializer
{
    /**
     * Returns array of helper objects
     * @return array
     */
    private function autoInitializeArray(){
        return [
            'queryBuilder' => new QueryBuilder(),
        ];
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getInitializedObject($key){
        return $this->autoInitializeArray()[$key];
    }
}
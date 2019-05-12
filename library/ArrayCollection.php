<?php
namespace library;


class ArrayCollection
{
    private $collectionArray = [];

    public function add($collectionContent){
        $this->collectionArray[] = $collectionContent;
    }
    public function getCollection(){
        return array_map(function($collectionObject){
            return unserialize($collectionObject);
        }, $this->collectionArray);
    }
}
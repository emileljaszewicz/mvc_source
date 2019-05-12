<?php
namespace library;


use Entities\Userranks;
use Entities\Users;

class UsersCreator extends QueryBuilder
{
    private $userData;
    public function findUser(array $arrayData){
        $this->createQueryForTable('users');
        $this->selectData();
        $this->where($arrayData);
        $this->userData = $this->execQuery()->fetch(\PDO::FETCH_ASSOC);

        return $this->userData;
    }
    public function getUserObiect(){
        $pdoData = $this->userData;

        return new Users(['userId' => $pdoData['userId']]);
    }
    public function getUserRankObject(){
        $pdoData = $this->userData;
        $userRanks = new Userranks(["userRankId" => $pdoData['userRankId']]);

        if($userRanks->getRankName() !== null) {
            $userRankName = "\\userranks\\".$userRanks->getRankName();
            $userRankObject = new $userRankName();
            return $userRankObject;
        }
        else{
            return new \userranks\Owner();
        }
    }
}
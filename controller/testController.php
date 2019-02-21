<?php
namespace controller;
use controller\Controller;
use Entities\Privileges;
use Entities\User_ranks;
use Entities\Users;
use library\DBEntity;
use library\EntityObjectCreator;
use library\UsersCreator;

/**
 *@Privileges(grantedUser(\userranks\Owner))
 */
class testController extends Controller
{

    /**
     *@Privileges(grantedUser(\userranks\Administrator))
     *@SkipSearching(skip(true))
     */
    public function index(){


        echo 'aaa';

    }
    /**
     *@Privileges(grantedUser(\userranks\Owner))
     *@SkipSearching(skip(true))
     */
    public function getUsers(){
echo 'dsdsd';
//        $q = $this->queryBuilder();
//        $q->createQueryForTable('users');
//        $q->selectData();
//        $data = $q->execQuery();
//        $send = $data->fetchAll(\PDO::FETCH_ASSOC);
//
//        echo json_encode($send);
    }

    /**
     *@Privileges(grantedUser(\userranks\Owner))
     *@SkipSearching(skip(true))
     *@Privileges(printData())
     */
    public function save(){
        echo 'qqqq';
    }
    public function some(){
        $privileges = new Privileges(['user_rank_id' => 1]);
        $testEntity = new Users(['user_id' => 1]);
        $userRanks = new User_ranks();
        $testEntity->joinEntityData($privileges->getEntityData());

      var_dump($testEntity->joinEntityData($userRanks->getEntityData()));

    }
}
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
 *
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

//        $users = new Users();
//        $users->setName('zenon');
//        $users->setEmail('aaww@o2.po.com');
//        $users->setLogin('exe');
//        $users->setPassword('wwweeeqqq');
//        $users->setUserRankId(2);
//        $users->save();

    }
}
<?php
namespace controller;


use library\EntityObjectCreator;
use library\SessionManager;
use library\UsersCreator;

class loginController extends Controller
{

    /**
     *@Privileges(grantedUser(\userranks\Owner))
     *@SkipSearching(skip(true))
     */
    public function login(){
        if(isset($_POST['submit'])){

            $queryBuilder = $this->queryBuilder();
            $queryBuilder->createQueryForTable('users');
            $queryBuilder->selectData();
            $queryBuilder->where(['login' => $_POST['login'], 'password' => $_POST['password']]);
            $getPdoQuery = $queryBuilder;
            $data = $queryBuilder->execQuery();
            $pdoUser = $data->fetchAll(\PDO::FETCH_ASSOC);
            if(!empty($pdoUser)){

                $userEntityObject = new EntityObjectCreator($getPdoQuery);

                $sessionManager = $this->getSessionManager();
                $sessionManager->addSessionData('userClass', $userEntityObject);

            }
        }
    }
}
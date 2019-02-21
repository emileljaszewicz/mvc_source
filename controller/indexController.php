<?php
namespace controller;

/**
 *@Privileges(grantedUser(\userranks\Administrator))
 */
class indexController extends Controller
{

    /**
     *@Privileges(grantedUser(\userranks\Owner))
     *
     *@Privileges(printData())
     */
    public function login(){
        return $this->render('loginForm');
    }

    public function othermethod(){
echo 'qqq';
        return 'sdds';
    }
}
<?php
namespace controller;

///**
// *@Privileges(grantedUser(\userranks\Administrator))
// */
class indexController extends Controller
{

    public function login(){
        return $this->render('loginForm');
    }

    public function othermethod(){
echo 'qqq';
        return 'sdds';
    }
}
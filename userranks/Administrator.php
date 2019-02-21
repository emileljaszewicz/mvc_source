<?php
namespace userranks;


class Administrator extends UserRank
{
    public function __construct()
    {
    }

    public function seeHelloClass(){
        return 'Hi!! I am an Administrator Object';
    }
}
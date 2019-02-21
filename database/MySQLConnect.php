<?php
namespace database;

use PDO;

class MySQLConnect
{
    private $pdoClass;
    public function __construct()
    {
        $this->pdoClass = new PDO('mysql:host=localhost;dbname=rent_a_car', 'root');
    }
    public function connect(){
        return $this->pdoClass;
    }
}
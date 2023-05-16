<?php

namespace Db;

class Db {

    private $host = 'localhost';
    private $dbname = 'juegos_online';
    private $user = 'root';
    private $pass = '';
    private $pdo;

    private function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=".$this->host.";dbname=".$this->$dbname, $this->$user, $this->$pass);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getGeneros(){
        $this->pdo->query("SELECT * FROM generos");
    }

}
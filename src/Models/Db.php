<?php

namespace App\Models;

class Db {

    private $host = 'localhost';
    private $dbname = 'juegos_online';
    private $user = 'root';
    private $pass = '';
    private $pdo;

    public function __construct() {
        try {
            $conexion = new \PDO("mysql:host=".$this->host.";dbname=".$this->dbname, $this->user, $this->pass);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo = $conexion;
        } catch (\PDOException $e) {
            return die($e->getMessage());
        }
    }

    public function connect(){
        return $this->pdo;
    }
}
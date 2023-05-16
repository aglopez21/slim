<?php

namespace App\Models;

use App\Models\Db;

class Generos {

    private $pdo;

    public function __construct() {
        $db = new Db();
        $this->pdo = $db->connect();
    }

    public function get() {
        $query = "SELECT * FROM generos";
        return $this->pdo->query($query, \PDO::FETCH_ASSOC);
    }

    public function post() {
        $query = "";
        return $this->pdo->query($query);
    }

    public function put() {
        $query = "";
        return $this->pdo->query($query);
    }

    public function delete($id) {
        $query = "DELETE FROM generos WHERE id=".$id;
        return $this->pdo->query($query, \PDO::FETCH_ASSOC);
    }

}
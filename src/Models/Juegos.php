<?php

namespace App\Models;

use App\Models\Db;

class Juegos {

    private $db;

    public function __construct() {
        $db = new Db();
        $this->pdo = $db->connect();
    }

    public function get() {
        $query = "SELECT * FROM juegos";
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
        $query = "DELETE FROM juegos WHERE id=".$id;
        return $this->pdo->query($query, \PDO::FETCH_ASSOC);
    }

    public function buscar() {
        $query = "SELECT * FROM juegos";
        return $this->pdo->query($query, \PDO::FETCH_ASSOC);
    }

}
<?php

namespace App\Models;

use App\Models\Db;

class Plataformas {

    private $db;

    public function __construct() {
        $db = new Db();
        $this->pdo = $db->connect();
    }

    public function get() {
        $query = "SELECT * FROM plataformas";
        return $this->pdo->query($query, \PDO::FETCH_ASSOC);
    }

    public function post($data) {
        $query = "INSERT INTO plataformas (nombre) VALUES ('".$data['nombre']."')";
        return $this->pdo->query($query);
    }

    public function put($id, $data) {
        $query = "UPDATE plataformas SET nombre='".$data['nombre']."' WHERE id=".$id;
        return $this->pdo->query($query);
    }

    public function delete($id) {
        $query = "DELETE FROM plataformas WHERE id=".$id;
        return $this->pdo->query($query, \PDO::FETCH_ASSOC);
    }

}
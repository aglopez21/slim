<?php

namespace App\Models;

use App\Models\Db;

class Generos {

    private $pdo;

    public function __construct() {
        $db = new Db();
        $this->pdo = $db->connect();
    }

    public static function get() {
        return "GET a Géneros";
    }

    public static function post() {
        return "POST a Géneros";
    }

    public static function put() {
        return "PUT a Géneros";
    }

    public static function delete() {
        return "\DELETE a Géneros";
    }

}
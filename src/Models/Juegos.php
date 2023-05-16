<?php

namespace App\Models;

use App\Models\Db;

class Juegos {

    private $db;

    public function __construct() {
        $this->db = new Db();
    }

    public static function get() {
        return "GET a Juegos";
    }

    public static function post() {
        return "POST a Juegos";
    }

    public static function put() {
        return "PUT a Juegos";
    }

    public static function delete() {
        return "\DELETE a Juegos";
    }

    public static function buscar() {
        return "GET a Búsqueda";
    }

}
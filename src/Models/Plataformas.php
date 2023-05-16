<?php

namespace App\Models;

use App\Models\Db;

class Plataformas {

    private $db;

    public function __construct() {
        $this->db = new Db();
    }

    public static function get() {
        return "GET a Plataformas";
    }

    public static function post() {
        return "POST a Plataformas";
    }

    public static function put() {
        return "PUT a Plataformas";
    }

    public static function delete() {
        return "\DELETE a Plataformas";
    }

}
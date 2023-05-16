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

    public function post($data) {
        $query = "";
        return $this->pdo->query($query);
    }

    public function put($data) {
        $query = "";
        return $this->pdo->query($query);
    }

    public function delete($id) {
        $query = "DELETE FROM juegos WHERE id=".$id;
        return $this->pdo->query($query, \PDO::FETCH_ASSOC);
    }

    public function buscar($params = array()) {
        //Obtenemos los parametros por $params = pueden existir nombre, plataforma y género, por eso se concatena los resultados de los condicionantes
        $condiciones = (isset($params['nombre']) ? ' AND nombre="'.$params['nombre'].'"' : '');
        $condiciones .= (isset($params['plataforma']) ? ' AND id_plataforma="'.$params['plataforma'].'"' : '');
        $condiciones .= (isset($params['genero']) ? ' AND id_genero="'.$params['genero'].'"' : '');
        //Realizamos el string del QUERY
        $query = "SELECT * FROM juegos WHERE (1=1)".$condiciones;
        //Retornamos la consulta
        return $this->pdo->query($query, \PDO::FETCH_ASSOC);
    }

}
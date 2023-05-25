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

    public function post($data,$img) {
        $tipo_imagen = $img['imagen']->getClientMediaType();
        $imagen=base64_encode(file_get_contents($img['imagen']->getFilePath()));

        $query = "INSERT INTO juegos(`nombre`, `imagen`, `tipo_imagen`, `descripcion`, `url`, `id_genero`, `id_plataforma`) VALUE('".$data['nombre']."', '".$imagen."', '".$tipo_imagen."', '".$data['descripcion']."', '".$data['url']."', '".$data['id_genero']."', '".$data['id_plataforma']."')";
        return $this->pdo->query($query);
    }

    public function put($data) {

        var_dump($data);
        $modificaciones="";
        if(isset($data['nombre'])){
            $modificaciones='nombre="'.$data['nombre'].'"';
    }
        if(isset($data['imagen'])){
            $modificaciones="";
        }
        if(isset($data['descripcion'])){
            $modificaciones=' AND `descripcion`="'.$data['descripcion'].'"';
}
        if(isset($data['url'])){
            $modificaciones=' AND `url`="'.$data['url'].'"';
        }
        if(isset($data['id_genero'])){
            $modificaciones=' AND `id_genero`="'.$data['id_genero'].'"';
        }
        if(isset($data['id_plataforma'])){
            $modificaciones=' AND `id_plataforma`="'.$data['id_plataforma'].'"';
        }

        $query = "UPDATE juegos SET .$modificaciones."; //WHERE ".$data['id'];
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
        $condiciones .= (isset($params['orderby']) ? ' ORDER BY nombre '.$params['orderby'] : '');
        //Realizamos el string del QUERY
        $query = "SELECT * FROM juegos WHERE (1=1)".$condiciones;
        //Retornamos la consulta
        return $this->pdo->query($query, \PDO::FETCH_ASSOC);
    }

}
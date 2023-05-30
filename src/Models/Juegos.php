<?php

namespace App\Models;

use App\Models\Db;

class Juegos {

    private $pdo;

    public function __construct() {
        $db = new Db();
        $this->pdo = $db->connect();
    }

    public function get() {
        $query = "SELECT * FROM juegos";
        return $this->pdo->query($query);
    }

    public function post($data,$img) {

        $tipo_imagen = $img['imagen']->getClientMediaType();
        $imagen=base64_encode(file_get_contents($img['imagen']->getFilePath()));

        $arregloFields = [];
        $arregloValues = [];

        if(isset($data->nombre) && !empty($data->nombre)){
            $arregloFields[] = '`nombre`';
            $arregloValues[] = "'".$data->nombre."'";
        }
        if(isset($imagen) && !empty($imagen)){
            $arregloFields[] = '`imagen`';
            $arregloValues[] = "'".$imagen."'";

            $arregloFields[] = '`tipo_imagen`';
            $arregloValues[] = "'".$tipo_imagen."'";
        }
        if(isset($data->descripcion) && !empty($data->descripcion)){
            $arregloFields[] = '`descripcion`';
            $arregloValues[] = "'".$data->descripcion."'";
        }
        if(isset($data->url) && !empty($data->url)){
            $arregloFields[] = '`url`';
            $arregloValues[] = "'".$data->url."'";
        }
        if(isset($data->id_genero) && !empty($data->id_genero)){
            $arregloFields[] = '`id_genero`';
            $arregloValues[] = "".$data->id_genero."";
        }
        if(isset($data->id_plataforma) && !empty($data->id_plataforma)){
            $arregloFields[] = '`id_plataforma`';
            $arregloValues[] = "".$data->id_plataforma."";
        }

        $fieldsArr = implode(',' , $arregloFields);
        $valuesArr = implode(',' , $arregloValues);

        $query = "INSERT INTO juegos($fieldsArr) VALUE($valuesArr)";
        return $this->pdo->query($query);
    }

    public function put($data, $img = NULL) {

        $arregloSET = [];

        if(isset($data->nombre) && !empty($data->nombre)){
            $arregloSET[]='`nombre`="'.$data->nombre.'"';
    }
        if(isset($img)){
            $imagen=base64_encode(file_get_contents($img['imagen']->getFilePath()));
            $tipo_imagen = $img['imagen']->getClientMediaType();

            $arregloSET[]='`imagen`="'.$imagen.'"';
            $arregloSET[]='`tipo_imagen`="'.$tipo_imagen.'"';
        }
        if(isset($data->descripcion) && !empty($data->descripcion)){
            $arregloSET[]='`descripcion`="'.$data->descripcion.'"';
}
        if(isset($data->url) && !empty($data->url)){
            $arregloSET[]='`url`="'.$data->url.'"';
        }
        if(isset($data->id_genero) && !empty($data->id_genero)){
            $arregloSET[]='`id_genero`="'.$data->id_genero.'" ';
        }
        if(isset($data->id_plataforma) && !empty($data->id_plataforma)){
            $arregloSET[]='`id_plataforma`="'.$data->id_plataforma.'"';
        }

        $setsArr = implode(',' , $arregloSET);

        $query = "UPDATE juegos SET $setsArr WHERE  `id`=".$data->id;
        return $this->pdo->query($query);
    }

    public function delete($id) {
        $query = "DELETE FROM juegos WHERE".$id;
        return $this->pdo->query($query, \PDO::FETCH_ASSOC);
    }

    public function buscar($data) {
        //Obtenemos los parametros por $data = pueden existir nombre, plataforma y género, por eso se concatena los resultados de los condicionantes
        $condiciones = (isset($data->nombre) ? ' AND nombre="'.$data->nombre.'"' : '');
        $condiciones .= (isset($data->plataforma) ? ' AND id_plataforma="'.$data->plataforma.'"' : '');
        $condiciones .= (isset($data->genero) ? ' AND id_genero="'.$data->genero.'"' : '');
        $condiciones .= (isset($data->orderby) ? ' ORDER BY nombre '.$data->orderby : '');
        //Realizamos el string del QUERY
        $query = "SELECT * FROM juegos WHERE (1=1)".$condiciones;
        //Retornamos la consulta
        return $this->pdo->query($query, \PDO::FETCH_ASSOC);
    }

}
<?php

namespace App\Models;

use App\Models\Db;

class Juegos {

    //Declaramos una propiedad del tipo private para almacenar la conexión a la base de datos
    private $pdo;

    //Generamos el construct de la clase
    public function __construct() {
        //Instanciamos una nueva conexión a la base de datos
        $db = new Db();
        //Llamamos al método de conexión y lo asignamos a la propiedad privada de la clase $pdo
        $this->pdo = $db->connect();
    }

    //Generamos un método para una solicitud GET
    public function get() {
        //Construimos el Query String para obtener de la tabla Juegos todos los datos almacenados
        $query = "SELECT * FROM juegos";
        //Retornamos la ejecución del query
        return $this->pdo->query($query);
    }

    //Generamos un método para una solicitud POST
    public function post($data) {

        //Inicializamos arreglos para guardar la información que proveé el usuario de la API
        //$arregloFields contendrá los campos en donde se almacenará el dato provisto
        $arregloFields = [];
        //$arregloValues contendrá los datos para cada campo del primer arreglo
        $arregloValues = [];

        //
        if(isset($data->nombre) && !empty($data->nombre)){
            $arregloFields[] = '`nombre`';
            $arregloValues[] = "'".$data->nombre."'";
        }
        if(isset($imagen) && !empty($imagen)){
            $arregloFields[] = '`imagen`';
            $arregloValues[] = "'".$data->imagen."'";

            $arregloFields[] = '`tipo_imagen`';
            $arregloValues[] = "'".$data->imagen_tipo."'";
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

        //Retornamos la ejecución del query
        return $this->pdo->query($query);
    }

    public function put($data) {

        $arregloSET = [];

        if(isset($data->nombre) && !empty($data->nombre)){
            $arregloSET[]='`nombre`="'.$data->nombre.'"';
    }
        if(isset($data->imagen) && isset($data->imagen_tipo)){
            $arregloSET[]='`imagen`="'.$data->imagen.'"';
            $arregloSET[]='`tipo_imagen`="'.$data->imagen_tipo.'"';
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

        //Retornamos la ejecución del query
        return $this->pdo->query($query);
    }

    //Generamos un método para una solicitud DELETE
    public function delete($id) {
        //Construimos el Query String que borrará el Juego donde juego.id sea igual al valor pasado por parámetro
        $query = "DELETE FROM juegos WHERE".$id;
        //Retornamos la ejecución del query
        return $this->pdo->query($query);
    }

    //Generamos un método para una solicitud GET, pero que requiere filtrar datos
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
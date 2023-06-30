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

        //Comprobamos que exista y no esté vacío $data->nombre
        if(isset($data->nombre) && !empty($data->nombre)){
            $arregloFields[] = '`nombre`';
            $arregloValues[] = "'".$data->nombre."'";
        }
        //Comprobamos que exista y no esté vacío $data->imagen
        if(isset($imagen) && !empty($imagen)){
            $arregloFields[] = '`imagen`';
            $arregloValues[] = "'".$data->imagen."'";

            $arregloFields[] = '`tipo_imagen`';
            $arregloValues[] = "'".$data->imagen_tipo."'";
        }
        //Comprobamos que exista y no esté vacío $data->descripcion
        if(isset($data->descripcion) && !empty($data->descripcion)){
            $arregloFields[] = '`descripcion`';
            $arregloValues[] = "'".$data->descripcion."'";
        }
        //Comprobamos que exista y no esté vacío $data->url
        if(isset($data->url) && !empty($data->url)){
            $arregloFields[] = '`url`';
            $arregloValues[] = "'".$data->url."'";
        }
        //Comprobamos que exista y no esté vacío $data->id_genero
        if(isset($data->id_genero) && !empty($data->id_genero)){
            $arregloFields[] = '`id_genero`';
            $arregloValues[] = "".$data->id_genero."";
        }
        //Comprobamos que exista y no esté vacío $data->id_plataforma
        if(isset($data->id_plataforma) && !empty($data->id_plataforma)){
            $arregloFields[] = '`id_plataforma`';
            $arregloValues[] = "".$data->id_plataforma."";
        }

        //Convertimos los arreglos en un string concatenados cada items por una ', '
        $fieldsArr = implode(',' , $arregloFields);
        $valuesArr = implode(',' , $arregloValues);

        //Construimos el Query String con los campos y valores concatenados
        $query = "INSERT INTO juegos($fieldsArr) VALUE($valuesArr)";

        //Retornamos la ejecución del query
        return $this->pdo->query($query);
    }

    public function put($id, $data) {

        $arregloSET = [];

        //Comprobamos que exista y no esté vacío $data->nombre
        if(isset($data->nombre) && !empty($data->nombre)){
            $arregloSET[]='`nombre`="'.$data->nombre.'"';
        }
        //Comprobamos que exista y no esté vacío tanto $data->imagen como $data->imagen_tipo
        if(isset($data->imagen) && !empty($data->imagen) && isset($data->imagen_tipo) && !empty($data->imagen_tipo)){
            $arregloSET[]='`imagen`="'.$data->imagen.'"';
            $arregloSET[]='`tipo_imagen`="'.$data->imagen_tipo.'"';
        }
        //Comprobamos que exista y no esté vacío $data->descripcion
        if(isset($data->descripcion) && !empty($data->descripcion)){
            $arregloSET[]='`descripcion`="'.$data->descripcion.'"';
        }
        //Comprobamos que exista y no esté vacío $data->url
        if(isset($data->url) && !empty($data->url)){
            $arregloSET[]='`url`="'.$data->url.'"';
        }
        //Comprobamos que exista y no esté vacío $data->id_genero
        if(isset($data->id_genero) && !empty($data->id_genero)){
            $arregloSET[]='`id_genero`="'.$data->id_genero.'" ';
        }
        //Comprobamos que exista y no esté vacío $data->id_plataforma
        if(isset($data->id_plataforma) && !empty($data->id_plataforma)){
            $arregloSET[]='`id_plataforma`="'.$data->id_plataforma.'"';
        }

        //Convertimos el arreglo en un string concatenando cada items por una ', '
        $setsArr = implode(',' , $arregloSET);

        //Construimos el Query String con los campos y valores concatenados
        $query = "UPDATE juegos SET $setsArr WHERE  `id`=".$id;

        //Retornamos la ejecución del query
        return $this->pdo->query($query);
    }

    //Generamos un método para una solicitud DELETE
    public function delete($id) {
        //Construimos el Query String que borrará el Juego donde juego.id sea igual al valor pasado por parámetro
        $query = "DELETE FROM juegos WHERE id=".$id;
        //Retornamos la ejecución del query
        return $this->pdo->query($query);
    }

    //Generamos un método para una solicitud GET, pero que requiere filtrar datos
    public function buscar($data) {
        //Obtenemos los parametros por $data = pueden existir nombre, plataforma y género, por eso se concatena los resultados de los condicionantes
        $condiciones = (isset($data['nombre']) && (!empty($data['nombre'])) ? ' AND j.nombre LIKE "%'.$data['nombre'].'%"' : '');
        $condiciones .= (isset($data['plataforma']) && (!empty($data['plataforma'])) ? ' AND j.id_plataforma="'.$data['plataforma'].'"' : '');
        $condiciones .= (isset($data['genero']) && (!empty($data['genero'])) ? ' AND j.id_genero="'.$data['genero'].'"' : '');
        $condiciones .= (isset($data['orderby']) && (!empty($data['orderby'])) ? ' ORDER BY j.nombre '.$data['orderby'] : '');
        //Realizamos el string del QUERY
        $query = "SELECT j.*, g.nombre nombre_genero, p.nombre nombre_plataforma FROM juegos j INNER JOIN generos g ON j.id_genero = g.id INNER JOIN plataformas p ON p.id = j.id_plataforma WHERE (1=1)".$condiciones;
        //Retornamos la consulta
        return $this->pdo->query($query, \PDO::FETCH_ASSOC);
    }

}
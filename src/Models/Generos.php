<?php

namespace App\Models;

use App\Models\Db;

class Generos {

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
        //Construimos el Query String para obtener de la tabla Géneros todos los datos almacenados
        $query = "SELECT * FROM generos";
        //Retornamos la ejecución del query
        return $this->pdo->query($query, \PDO::FETCH_ASSOC);
    }

    //Generamos un método para una solicitud POST
    public function post($data) {
        //Construimos el Query String para insertar un nuevo Género. Este solo guarda un nombre, y al insertarse se autogenera su id
        $query = "INSERT INTO generos (nombre) VALUES ('".$data->nombre."')";
        //Retornamos la ejecución del query
        return $this->pdo->query($query);
    }

    //Generamos un método para una solicitud PUT
    public function put($data) {
        //Construimos el Query String para actualizar un Género. Este solo guarda un nuevo nombre, si generos.id es igual al id enviado por el usuario
        $query = "UPDATE generos SET nombre='".$data->nombre."' WHERE id=".$data->id;
        //Retornamos la ejecución del query
        return $this->pdo->query($query);
    }

    //Generamos un método para una solicitud DELETE
    public function delete($id) {
        //Construimos el Query String que borrará el Género donde generos.id sea igual al valor pasado por parámetro
        $query = "DELETE FROM generos WHERE id=".$id;
        //Retornamos la ejecución del query
        return $this->pdo->query($query);
    }

}
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
    public function get($data) {
        if(isset($data['id'])){
            //Construimos el Query String para obtener de la tabla Géneros los datos del género solicitaod
            $query = "SELECT * FROM generos WHERE id=".$data['id'];
        }else{
            //Construimos el Query String para obtener de la tabla Géneros todos los datos almacenados
            $query = "SELECT * FROM generos";
        }
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
    public function put($id, $data) {
        //Construimos el Query String para actualizar un Género. Este solo guarda un nuevo nombre, si generos.id es igual al id enviado por el usuario
        $query = "UPDATE generos SET nombre='".$data->nombre."' WHERE id=".$id;
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
    
    //Generamos un método para comprobar si existe algún juego con el id_genero a eliminar
    public function enJuego($id){
        $query = $this->pdo->query("SELECT * FROM juegos WHERE id_genero=$id");
        return ($query->rowCount());
    }

    //Comprobar existenciaID
    public function existe($id){
        $query = $this->pdo->query("SELECT * FROM generos WHERE id=$id");
        if($query->rowCount() > 0){
            return true;
        }
        return false;
    }

}
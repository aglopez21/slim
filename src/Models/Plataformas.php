<?php

namespace App\Models;

use App\Models\Db;

class Plataformas {

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
            //Construimos el Query String para obtener de la tabla Plataformas los datos del plataforma solicitada
            $query = "SELECT * FROM plataformas WHERE id=".$data['id'];
        }else{
            //Construimos el Query String para obtener de la tabla Plataformas todos los datos almacenados
            $query = "SELECT * FROM plataformas";
        }
        //Retornamos la ejecución del query
        return $this->pdo->query($query, \PDO::FETCH_ASSOC);
    }

    //Generamos un método para una solicitud POST
    public function post($data) {
        //Construimos el Query String para insertar un nueva Plataforma. Este solo guarda un nombre, y al insertarse se autogenera su id
        $query = "INSERT INTO plataformas (nombre) VALUES ('".$data->nombre."')";
        //Retornamos la ejecución del query
        return $this->pdo->query($query);
    }

    //Generamos un método para una solicitud PUT
    public function put($id, $data) {
        //Construimos el Query String para actualizar una Plataforma. Este solo guarda un nuevo nombre, si plataformas.id es igual al id enviado por el usuario
        $query = "UPDATE plataformas SET nombre='".$data->nombre."' WHERE id=".$id;
        //Retornamos la ejecución del query
        return $this->pdo->query($query);
    }

    //Generamos un método para una solicitud DELETE
    public function delete($id) {
        //Construimos el Query String que borrará la Plataforma donde plataformas.id sea igual al valor pasado por parámetro
        $query = "DELETE FROM plataformas WHERE id=".$id;
        //Retornamos la ejecución del query
        return $this->pdo->query($query);
    }

    //Generamos un método para comprobar si existe algún juego con el id_plataforma a eliminar
    public function enJuego($id){
        $query = $this->pdo->query("SELECT * FROM juegos WHERE id_plataforma=$id");
        return ($query->rowCount());
    }

}
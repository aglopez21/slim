<?php

namespace App\Models;

class Db {
    //Declaramos las propiedades de la clase que usaremos para la configuración de la conexión a Base de Datos
    private $host = 'localhost';
    private $dbname = 'juegos_online';
    private $user = 'root';
    private $pass = '';
    private $pdo;

    //Generamos método construct
    public function __construct() {
        //Intentamos conectarnos a la base de datos con la config de las propiedades de la clase
        try {
            $conexion = new \PDO("mysql:host=".$this->host.";dbname=".$this->dbname, $this->user, $this->pass);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            //En caso de éxito se guardará en la propiedad $pdo de esta clase
            $this->pdo = $conexion;
        } catch (\PDOException $e) {
            return die($e->getMessage());
        }
    }

    //Construimos un método de conexión que nos retorne la conexión almacenada en la propiedad $pdo de esta clase
    public function connect(){
        //Retornamos propiedad $pdo de la clase
        return $this->pdo;
    }
}
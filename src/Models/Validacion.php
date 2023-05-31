<?php

namespace App\Models;

class Validacion {

    //Declaramos una propiedad "configuración" en la clase que nos servirá para distintos métodos
    private $config;

    public function __construct(){
        //Asignamos un límite para Descripción
        $this->config['topeDesc'] = 255;
        //Asignamos un límite para Url
        $this->config['topeUrl'] = 80;
        //Asignamos un arreglo para almacenar los tipos de imagen que admitiremos
        $this->config['imagen_tipos'][] = 'image/jpg';
        $this->config['imagen_tipos'][] = 'image/png';
        $this->config['imagen_tipos'][] = 'image/jpeg';
    }

    //Método de validacion de nombre
    public function validarNombre($nombre){
        //Retornamos si la URL está (FALSE) o no está vacía (TRUE) Y la URL es (TRUE) o no es (FALSE) un string
        return !empty($nombre) AND is_string($nombre);
    }
    
    //Método de validación descripción
    public function validarDescripcion($desc){
        //Retornamos si la URL está (FALSE) o no está vacía (TRUE) Y la URL es (TRUE) o no es (FALSE) un string Y la extensión de la URL está (TRUE) o no está (FALSE) dentro del límite establecido en el arreglo $config siendo este propiedad de la clase
       return (!empty($desc)) AND (is_string($desc)) AND (strlen($desc)<=$this->config['topeDesc']);
    }
    
    //Método de validación URL
    public function validarURL($url){
        //Retornamos si la URL está (FALSE) o no está vacía (TRUE) Y la URL es (TRUE) o no es (FALSE) un string Y la extensión de la URL está (TRUE) o no está (FALSE) dentro del límite establecido en el arreglo $config siendo este propiedad de la clase
        return (!empty($url)) AND (is_string($url)) AND ( strlen($url)<=$this->config['topeUrl']);
    }
    
    //Método de validación de imagen
    public function validarImagen($img){
        //Retornamos si la extensión de imagen pasada por parametros está (TRUE) o no está (FALSE) dentro de nuestro arreglo
        return in_array($img,$this->config['imagen_tipos']);
    }

}
    


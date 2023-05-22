<?php

    class Validacion{
        private $config = array("topeDesc" => "255", "topeUrl" => 80);
        private $extensiones=array('image/jpg','image/png','image/jpeg');
       

        public function _construct(){
            return true;
        }

        //funcion de validacion de nombre
        public function validarNombre($nombre){
            return is_string($nombre) and  !empty($nombre);
        }
    
    
        //Función de validación descripción
         public function validarDescripcion($desc){
           return (!empty($desc)) and (is_string($desc)) and (strlen($desc)<=$config['topeDesc']);
        }
    
    
        //Función de validación URL
        function validarURL($url){
           return  (!empty($url)) and (is_string($url)) and ( strlen($url)<=$config['topeUrl']);
        }
    
        //Función de validación de imagen
        function validarImagen($img){
            return in_array($img,$extensiones);

        }

    }
    

?>

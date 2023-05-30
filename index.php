<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Models\Db;
use App\Models\Juegos as Juegos;
use App\Models\Generos as Generos;
use App\Models\Plataformas as Plataformas;
use App\Models\Validacion;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();    

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write(json_encode('{"msg": "Bienvenido a la API!"}', JSON_UNESCAPED_UNICODE));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

/*

    ESQUEMA

    //Obtenemos los datos
    //Comprobamos que no sean nulos
        //Iniciamos un nuevo objeto de Validación
        //Comprobamos que los datos sean válidos
            //Iniciamos un nuevo objeto del tipo correspondiente
            //Ejecutamos método correspondiente
            //Comprobamos estado de la ejecución
                //Si fue exitosa entra acá
                //Si obtuvo un error entra acá
            //Si la validación falló entra acá
        //Si no existían datos entra acá
    //Retornamos respuesta

*/


//a) Crear un nuevo género: implementar un endpoint para crear un nuevo genero en la tabla de géneros. El endpoint debe permitir enviar el nombre.
$app->post('/generos', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = json_decode($request->getBody()->getContents());
    //Comprobamos que no sean nulos
    if(isset($data)){
        //Iniciamos un nuevo objeto de Validación
        $validacion = new Validacion();
        //Comprobamos que los datos sean válidos
        if($validacion->validarNombre($data->nombre)){
            //Iniciamos un nuevo objeto del tipo correspondiente
            $generos = new Generos();
            //Ejecutamos método correspondiente
            $post = $generos->post($data);
            //Comprobamos estado de la ejecución
            if($post->rowCount() > 0){
                //Si fue exitosa entra acá
                $response->getBody()->write(json_encode('{"msg": "Género insertado correctamente."}', JSON_UNESCAPED_UNICODE));
                $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }else{
                //Si obtuvo un error entra acá
                $response->getBody()->write(json_encode('{"error": "Error al insertar el género."}', JSON_UNESCAPED_UNICODE));
                $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }else{
            //Si la validación falló entra acá
            $response->getBody()->write(json_encode('{"error": "Falló la validación."}', JSON_UNESCAPED_UNICODE)); 
            $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }else{
        //Si no existían datos entra acá
        $response->getBody()->write(json_encode('{"error": "No se envió ningún dato."}', JSON_UNESCAPED_UNICODE)); 
        $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    //Retornamos respuesta
    return $response;
});

//b) Actualizar información de un género: implementar un endpoint para actualizar la información de un género existente en la tabla de géneros. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
$app->put('/generos', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = json_decode($request->getBody()->getContents());
    //Comprobamos que no sean nulos
    if(isset($data)){
        //Iniciamos un nuevo objeto de Validación
        $validacion = new Validacion();
        //Comprobamos que los datos sean válidos
        if($validacion->validarNombre($data->nombre)){
            //Iniciamos un nuevo objeto del tipo correspondiente
            $generos = new Generos();
            //Ejecutamos método correspondiente
            $put = $generos->put($data);
            //Comprobamos estado de la ejecución
            if($put->rowCount() > 0){
                //Si fue exitosa entra acá
                $response->getBody()->write(json_encode('{"msg": "Género actualizado"}', JSON_UNESCAPED_UNICODE));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } else {
                //Si obtuvo un error entra acá
                $response->getBody()->write(json_encode('{"msg": "Error al actualizar el genero."}', JSON_UNESCAPED_UNICODE))->withStatus(400);
            }
        } else{
            //Si la validación falló entra acá
            $response->getBody()->write(json_encode('{"msg": "Error en la validación del nombre."}', JSON_UNESCAPED_UNICODE))->withStatus(400);
        }
    }else{
        //Si no existían datos entra acá
        $response->getBody()->write(json_encode('{"msg": "No se han enviado datos."}', JSON_UNESCAPED_UNICODE))->withStatus(400);
    }
    //Retornamos respuesta
    return $response;
});

//c) Eliminar un género: el endpoint debe permitir enviar el id del genero y eliminarlo de la tabla.
$app->delete('/generos', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = json_decode($request->getBody()->getContents());
    //Comprobamos que no sean nulos
    if(isset($data)){
        //Iniciamos un nuevo objeto del tipo correspondiente
        $generos = new Generos();
        //Ejecutamos método correspondiente
        $delete = $generos->delete($data->id);
        //Comprobamos estado de la ejecución
        if($delete->rowCount() > 0){
            //Si fue exitosa entra acá
            $response->getBody()->write(json_encode('{"msg": "Género eliminado."}', JSON_UNESCAPED_UNICODE));
            $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }else{
            //Si obtuvo un error entra acá
            $response->getBody()->write(json_encode('{"msg": "ID no encontrado."}', JSON_UNESCAPED_UNICODE));
            $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }else{
        //Si no existían datos entra acá
        $response->getBody()->write(json_encode('{"error": "Ocurrió algún error en la consulta."}', JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    //Retornamos respuesta
    return $response;
});

//d) Obtener todos los géneros: implemente un endpoint para obtener todos los géneros de la tabla.
$app->get('/generos', function (Request $request, Response $response, $args) {
    //Iniciamos un nuevo objeto del tipo correspondiente
    $generos = new Generos();
    //Ejecutamos método correspondiente
    $get = $generos->get();
    //Comprobamos estado de la ejecución
    if($get->rowCount() > 0){
        //Si fue exitosa entra acá
        $response->getBody()->write(json_encode($get->fetchAll(), JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        //Si obtuvo un error entra acá
        $response->getBody()->write(json_encode('{"msg": "No se encontraron datos en la BD."}', JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
    //Retornamos respuesta
    return $response;
});

//e) Crear una nueva plataforma: implementar un endpoint para crear una nueva plataforma en la tabla de plataformas. El endpoint debe permitir enviar el nombre.
$app->post('/plataformas', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = json_decode($request->getBody()->getContents());
    //Comprobamos que no sean nulos
    if(isset($data)){
        //Iniciamos un nuevo objeto de Validación
        $validacion = new Validacion();
        //Comprobamos que los datos sean válidos
        if($validacion->validarNombre($data->nombre)){
            //Iniciamos un nuevo objeto del tipo correspondiente
            $plataformas = new Plataformas();
            //Ejecutamos método correspondiente
            $post = $plataformas->post($data);
            //Comprobamos estado de la ejecución
            if($post->rowCount() > 0){
                //Si fue exitosa entra acá
                $response->getBody()->write(json_encode('{"msg": "Plataforma insertada correctamente."}', JSON_UNESCAPED_UNICODE));
                $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }else{
                //Si obtuvo un error entra acá
                $response->getBody()->write(json_encode('{"error": "Error al insertar la plataforma."}', JSON_UNESCAPED_UNICODE));
                $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }else{
            //Si la validación falló entra acá
            $response->getBody()->write(json_encode('{"error": "Falló la validación."}', JSON_UNESCAPED_UNICODE)); 
            $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }else{
        //Si no existían datos entra acá
        $response->getBody()->write(json_encode('{"error": "No se envió ningún dato."}', JSON_UNESCAPED_UNICODE)); 
        $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    //Retornamos respuesta
    return $response;
});

//f) Actualizar información de una plataforma: implementar un endpoint para actualizar la información de una plataforma existente en la tabla de plataformas. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
$app->put('/plataformas', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = json_decode($request->getBody()->getContents());
    //Comprobamos que no sean nulos
    if(isset($data)){
        //Iniciamos un nuevo objeto de Validación
        $validacion = new Validacion();
        //Comprobamos que los datos sean válidos
        if($validacion->validarNombre($data->nombre)){
            //Iniciamos un nuevo objeto del tipo correspondiente
            $plataformas = new Plataformas();
            //Ejecutamos método correspondiente
            $put = $plataformas->put($data);
            //Comprobamos estado de la ejecución
            if($put->rowCount() > 0){
                //Si fue exitosa entra acá
                $response->getBody()->write(json_encode('{"msg": "Plataforma Actualizada."}', JSON_UNESCAPED_UNICODE));
                $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }else{
                //Si obtuvo un error entra acá
                $response->getBody()->write(json_encode('{"msg": "ID no encontrado."}"', JSON_UNESCAPED_UNICODE));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }
        }else{
            //Si la validación falló entra acá
            $response->getBody()->write(json_encode('{"error": "Fallo la validacion."}', JSON_UNESCAPED_UNICODE));
            $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }else{
        //Si no existían datos entra acá
        $response->getBody()->write(json_encode('{"error": "No se envió ningún dato."}', JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    //Retornamos respuesta
    return $response;
});

//g) Eliminar una plataforma: el endpoint debe permitir enviar el id de la plataforma y eliminarla de la tabla.
$app->delete('/plataformas', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = json_decode($request->getBody()->getContents());
    //Comprobamos que no sean nulos
    if(isset($data)){
        //Iniciamos un nuevo objeto del tipo correspondiente
        $plataformas = new Plataformas();
        //Ejecutamos método correspondiente
        $delete = $plataformas->delete($data->id);
        //Comprobamos estado de la ejecución
        if($delete->rowCount() > 0){
            //Si fue exitosa entra acá
            $response->getBody()->write(json_encode('{"msg": "Plataforma eliminada."}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            //Si obtuvo un error entra acá
            $response->getBody()->write(json_encode('{"msg": "Plataforma no encontrada."}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }else{
        //Si no existían datos entra acá
        $response->getBody()->write(json_encode('{"error": "No se envió ningún dato."}', JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    //Retornamos respuesta
    return $response;
});

//h) Obtener todas las plataformas: implemente un endpoint para obtener todos los géneros de la tabla.
$app->get('/plataformas', function (Request $request, Response $response, $args) {
    //Iniciamos un nuevo objeto del tipo correspondiente
    $plataformas = new Plataformas();
    //Ejecutamos método correspondiente
    $get = $plataformas->get();
    //Comprobamos estado de la ejecución
    if($get->rowCount() > 0){
        //Si fue exitosa entra acá
        $response->getBody()->write(json_encode($get->fetchAll(), JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        //Si obtuvo un error entra acá
        $response->getBody()->write(json_encode('{"msg": "No se encontraron datos en la BD."}', JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
    //Retornamos respuesta
    return $response;
});

//i) Crear un nuevo juego: implementar un endpoint para crear un nuevo juego en la tabla de juegos. El endpoint debe permitir enviar el nombre, imagen, descripción, plataforma, URL y género.
$app->post('/juegos', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = json_encode($request->getParsedBody());
    $data = json_decode($data);
    $img = $request->getUploadedFiles();
    //Comprobamos que no sean nulos
    if(isset($data) && isset($img)){
        //Iniciamos un nuevo objeto de Validación
        $validacion = new Validacion();
        //Iniciamos un arreglo de errores
        $errores = [];
        //Comprobamos que los datos sean válidos
        if (!isset($data->nombre)) {
            $errores['error_nombre'] = 'No se envió el nombre';
        }else{
            (!$validacion->validarNombre($data->nombre)) ? $errores['error_nombre'] = 'Nombre inválido' : '';
        }
        if (!isset($img) || empty($img)) {
            $errores['error_imagen'] = 'No se envió la imagen';
        }else{
            (!$validacion->validarImagen($img['imagen']->getClientMediaType())) ? $errores['error_imagen'] = 'Imagen inválida' : '';
        }
        if (!isset($data->descripcion)) {
            $errores['error_descripcion'] = 'No se envió la descripción';
        }else{
            (!$validacion->validarDescripcion($data->descripcion)) ? $errores['error_descripcion'] = 'Descripción inválida' : '';
        }
        if (!isset($data->url)) {
            $errores['error_url'] = 'No se envió la URL';
        }else{
            (!$validacion->validarURL($data->url)) ? $errores['error_url'] = 'URL inválida' : '';
        }
        (!isset($data->id_genero)) ? $errores['error_genero'] = 'No se seleccionó ningún género' : '';
        (!isset($data->id_plataforma)) ? $errores['error_plataforma'] = 'No se seleccionó ninguna plataforma' : '';

        if(empty($errores)){
            //Iniciamos un nuevo objeto del tipo corresp ndiente
            $juegos = new Juegos();
            //Ejecutamos método correspondiente
            $post = $juegos->post($data,$img);
            //Comprobamos estado de la ejecución
            if($post){
                //Si fue exitosa entra acá
                $response->getBody()->write(json_encode('{"msg": "Juego  insertado correctamente."}', JSON_UNESCAPED_UNICODE));
                $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }else{
                //Si obtuvo un error entra acá
                $response->getBody()->write(json_encode('{"error": "Error al insertar el juego."}', JSON_UNESCAPED_UNICODE));
                $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }
        }else{
            //Si la validación falló entra acá
            $response->getBody()->write(json_encode($errores, JSON_UNESCAPED_UNICODE));
            $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }else{
        //Si no existían datos entra acá
            $response->getBody()->write(json_encode('{"error": "No se enviaron todos los datos requeridos."}', JSON_UNESCAPED_UNICODE));
            $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    //Retornamos respuesta
    return $response;

});

//j) Actualizar información de un juego: implementar un endpoint para actualizar la información de un juego existente en la tabla de juegos. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
$app->put('/juegos', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = json_encode($request->getParsedBody());
    $data = json_decode($data);
    $img = $request->getUploadedFiles();
    //Comprobamos que no sean nulos
    if(isset($data->id)){
        //Iniciamos un nuevo objeto de Validación
        $validacion = new Validacion();
        //Iniciamos un arreglo de errores
        $errores = [];
        //Comprobamos que los datos sean válidos
        (isset($data->nombre) && !$validacion->validarNombre($data->nombre)) ? $errores['error_nombre'] = 'Nombre inválido' : '';
        (isset($img) && !$validacion->validarImagen($img['imagen']->getClientMediaType())) ? $errores['error_imagen'] = 'Imagen inválida' : '';
        (isset($data->descripcion) && !$validacion->validarDescripcion($data->descripcion)) ? $errores['error_descripcion'] = 'Descripción inválida' : '';
        (isset($data->url) && !$validacion->validarURL($data->url)) ? $errores['error_url'] = 'URL inválida' : '';
        (!isset($data->id_genero)) ? $errores['error_genero'] = 'No se seleccionó ningún género' : '';
        (!isset($data->id_plataforma)) ? $errores['error_plataforma'] = 'No se seleccionó ninguna plataforma' : '';
        if(empty($errores)){
            //Iniciamos un nuevo objeto del tipo corresp ndiente
            $juegos = new Juegos();
            //Ejecutamos método correspondiente
            $post = $juegos->put($data,$img);
            //Comprobamos estado de la ejecución
            if($post){
                //Si fue exitosa entra acá
                $response->getBody()->write(json_encode('{"msg": "Juego actualizado correctamente."}', JSON_UNESCAPED_UNICODE));
                $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }else{
                //Si obtuvo un error entra acá
                $response->getBody()->write(json_encode('{"error": "Error al actualizar el juego."}', JSON_UNESCAPED_UNICODE));
                $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }
        }else{
            //Si la validación falló entra acá
            $response->getBody()->write(json_encode($errores, JSON_UNESCAPED_UNICODE));
            $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }else{
        //Si no existían datos entra acá
            $response->getBody()->write(json_encode('{"error": "No se envió el id del juego."}', JSON_UNESCAPED_UNICODE));
            $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    //Retornamos respuesta
    return $response;    
});

//k) Eliminar un juego: el endpoint debe permitir enviar el id del juego y eliminarlo de la tabla.
$app->delete('/juegos', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = json_decode($request->getBody()->getContents());
    //Comprobamos que no sean nulos
    if(isset($data)){
        //Iniciamos un nuevo objeto del tipo correspondiente
        $juegos = new Juegos();
        //Ejecutamos método correspondiente
        $delete = $juegos->delete($data->id);
        //Comprobamos estado de la ejecución
        if($delete->rowCount() > 0){
            //Si fue exitosa entra acá
            $response->getBody()->write(json_encode('{"msg": "Juego eliminado."}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            //Si obtuvo un error entra acá
            $response->getBody()->write(json_encode('{"msg": "Juego no encontrado."}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }else{
        //Si no existían datos entra acá
        $response->getBody()->write(json_encode('{"error": "No se envió ningún dato."}', JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    //Retornamos respuesta
    return $response;
});

//l) Obtener todos los juegos: implemente un endpoint para obtener todos los juegos de la tabla.
$app->get('/juegos', function (Request $request, Response $response, $args) {
    //Iniciamos un nuevo objeto del tipo correspondiente
    $juegos = new Juegos();
    //Ejecutamos método correspondiente
    $get = $juegos->get();
    //Comprobamos estado de la ejecución
    if($get->rowCount() > 0){
        //Si fue exitosa entra acá
        $response->getBody()->write(json_encode($get->fetchAll(), JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        //Si obtuvo un error entra acá
        $response->getBody()->write(json_encode('{"msg": "No se encontraron datos en la BD."}', JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
    //Retornamos respuesta
    return $response;
});

//m) Buscar juegos: implementar un endpoint que permita buscar juegos por nombre, plataforma y género. El endpoint deberá aceptar un nombre, un id de género, un id de plataforma y un orden por nombre (ASC o DESC)
$app->get('/buscar', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = json_decode($request->getBody()->getContents());
    //Iniciamos un nuevo objeto del tipo correspondiente
    $busqueda = new Juegos();
    //Ejecutamos método correspondiente
    $get = $busqueda->buscar($data);
    //Comprobamos estado de la ejecución
    if($get->rowCount() > 0){
        //Si fue exitosa entra acá
        $response->getBody()->write(json_encode($get->fetchAll(), JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        //Si obtuvo un error entra acá
        $response->getBody()->write(json_encode('{"msg": "No se encontraron datos en la BD."}', JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
    //Retornamos respuesta
    return $response;
});

$app->run();

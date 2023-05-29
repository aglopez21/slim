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

//a) Crear un nuevo género: implementar un endpoint para crear un nuevo genero en la tabla de géneros. El endpoint debe permitir enviar el nombre.
$app->post('/generos', function (Request $request, Response $response, $args) {
    //Capturo los campos enviados: nombre
    $data = $request->getQueryParams();
    if(isset($data)){
        $validacion = new Validacion();
    
        if($validacion->validarNombre($data['nombre'])){
            $generos = new Generos();
            $post = $generos->post($data);
            if($post){
                $response->getBody()->write(json_encode('{"msg": "Género insertado correctamente."}', JSON_UNESCAPED_UNICODE));
                $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }else{
                $response->getBody()->write(json_encode('{"error": "Error al insertar el género."}', JSON_UNESCAPED_UNICODE));
                $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }else{
            $response->getBody()->write(json_encode('{"error": "Falló la validación."}', JSON_UNESCAPED_UNICODE)); 
            $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }else{
        $response->getBody()->write(json_encode('{"error": "No se envió ningún dato."}', JSON_UNESCAPED_UNICODE)); 
        $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    return $response;
});

//b) Actualizar información de un género: implementar un endpoint para actualizar la información de un género existente en la tabla de géneros. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
$app->put('/generos/{id}', function (Request $request, Response $response, $args) {
    //Capturo los campos enviados: nombre e id
    $data = $request->getQueryParams();
    $validacion = new Validacion();
    if($validacion->validarNombre($data['nombre'])){
        $generos = new Generos();
        $put = $generos->put($args['id'], $data);
        if($put){
            $response->getBody()->write(json_encode('{"msg": "Género actualizado"}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode('{"msg": "Error al actualizar el genero."}', JSON_UNESCAPED_UNICODE))->withStatus(400);
        }
    }
    else

    return $response;
});

//c) Eliminar un género: el endpoint debe permitir enviar el id del genero y eliminarlo de la tabla.
$app->delete('/generos/{id}', function (Request $request, Response $response, $args) {
    $generos = new Generos();
    $delete = $generos->delete($args['id']);
    if($delete){
        //Si se ejecutó correctamente la consulta
        if($delete->rowCount() > 0){
            $response->getBody()->write(json_encode('{"msg": "Género eliminado."}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode('{"msg": "ID no encontrado."}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }else{
        //Si ocurrió un error
        $response->getBody()->write(json_encode('{"error": "Ocurrió algún error en la consulta."}', JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

//d) Obtener todos los géneros: implemente un endpoint para obtener todos los géneros de la tabla.
$app->get('/generos', function (Request $request, Response $response, $args) {
    $generos = new Generos();
    $get = $generos->get();
    if($get){
        //Si se ejecutó correctamente la consulta
        if($get->rowCount() > 0){
            $response->getBody()->write(json_encode($get->fetchAll(), JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode('{"msg": "No se encontraron datos en la BD."}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }else{
        //Si ocurrió un error
        $response->getBody()->write(json_encode('{"error": "Ocurrió algún error en la consulta."}', JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

//e) Crear una nueva plataforma: implementar un endpoint para crear una nueva plataforma en la tabla de plataformas. El endpoint debe permitir enviar el nombre.
$app->post('/plataformas', function (Request $request, Response $response, $args) {
    $data = $request->getQueryParams();
    if(isset($data)){
        $validacion = new Validacion();
    
        if($validacion->validarNombre($data['nombre'])){
            $plataformas = new Plataformas();
            $post = $plataformas->post($data);
            if($post){
                $response->getBody()->write(json_encode('{"msg": "Plataforma insertada correctamente."}', JSON_UNESCAPED_UNICODE));
                $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }else{
                $response->getBody()->write(json_encode('{"error": "Error al insertar la plataforma."}', JSON_UNESCAPED_UNICODE));
                $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }else{
            $response->getBody()->write(json_encode('{"error": "Falló la validación."}', JSON_UNESCAPED_UNICODE)); 
            $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }else{
        $response->getBody()->write(json_encode('{"error": "No se envió ningún dato."}', JSON_UNESCAPED_UNICODE)); 
        $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    return $response;
});

//f) Actualizar información de una plataforma: implementar un endpoint para actualizar la información de una plataforma existente en la tabla de plataformas. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
$app->put('/plataformas/{id}', function (Request $request, Response $response, $args) {
    //Capturo los campos enviados: nombre e id
    $data = $request->getQueryParams();
    $validacion=new Validacion();
    if($validacion->validarNombre($data['nombre'])){
        $plataformas = new Plataformas();
        $put = $plataformas->put($args['id'], $data);
        if($put){
        $response->getBody()->write(json_encode('{"msg": "Plataforma Actualizada."}', JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        else{
            $response->getBody()->write(json_encode('{"msg": "ID no encontrado."}"', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
    else{
        $response->getBody()->write(json_encode('{"error": "Fallo la validacion."}', JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json')->withStatus(400);

    }
    return $response;
});

//g) Eliminar una plataforma: el endpoint debe permitir enviar el id de la plataforma y eliminarla de la tabla.
$app->delete('/plataformas/{id}', function (Request $request, Response $response, $args) {
    $plataformas = new Plataformas();
    $delete = $plataformas->delete($args['id']);
    if($delete){
        //Si se ejecutó correctamente la consulta
        if($delete->rowCount() > 0){
            $response->getBody()->write(json_encode('{"msg": "Plataforma eliminada."}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode('{"msg": "Plataforma no encontrada."}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }else{
        //Si ocurrió un error
        $response->getBody()->write(json_encode('{"error": "Ocurrió algún error en la consulta."}', JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

//h) Obtener todas las plataformas: implemente un endpoint para obtener todos los géneros de la tabla.
$app->get('/plataformas', function (Request $request, Response $response, $args) {
    $plataformas = new Plataformas();
    $get = $plataformas->get();
    if($get){
        //Si se ejecutó correctamente la consulta
        if($get->rowCount() > 0){
            $response->getBody()->write(json_encode($get->fetchAll(), JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode('{"msg": "No se encontraron datos en la BD."}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }else{
        //Si ocurrió un error
        $response->getBody()->write(json_encode('{"error": "Ocurrió algún error en la consulta."}', JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

//i) Crear un nuevo juego: implementar un endpoint para crear un nuevo juego en la tabla de juegos. El endpoint debe permitir enviar el nombre, imagen, descripción, plataforma, URL y género.
$app->post('/juegos', function (Request $request, Response $response, $args) {
    //Capturo los campos enviados
    $img=$request->getUploadedFiles();
    $data = $request->getQueryParams();
    $validacion=new Validacion();
    $errores=[];
    //validamos el nombre
    if(!$validacion->validarNombre($data['nombre'])){
        $errores[]="Ha fallado la validacion del nombre";
    }

    if(!$validacion->validarURL($data['url'])){
        $errores[]="La url es invalida";
    }


    if(!$validacion->validarImagen($img['imagen']->getClientMediaType())){
        $errores[]="La extension de la imagen no es valida";
    }

    if(!$validacion->validarDescripcion($data['descripcion'])){
        $errores[]="Ha Fallado la validacion de la descripcion";
    }
 
    if(empty($errores)){
    $juegos = new Juegos();
    $post = $juegos->post($data,$img);
    if($post){
        $response->getBody()->write(json_encode('{"msg": "Juego  insertado correctamente."}', JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
    else{
        $response->getBody()->write(json_encode('{"error": "Error al insertar el juego."}', JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
    }
    else{
        $response->getBody()->write(json_encode($errores, JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    return $response;

});

//j) Actualizar información de un juego: implementar un endpoint para actualizar la información de un juego existente en la tabla de juegos. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
$app->put('/juegos', function (Request $request, Response $response, $args) {
    //Capturo los campos enviados
    $data = $request->getQueryParams();
    //primero deberia validar que el id exista dentro de los juegos en la bd
    $validacion = new Validacion();
    $errores=[];
    //si se se quiere modificar el nombre
    if(!!empty($data['nombre'])){
        if(!$validacion->validarNombre($data['nombre'])){
            $errores[]="El nombre no es valido";
        }
    }
    // si se quiere modificar la desc
    if(!empty($data['descripcion'])){
        if(!$validacion->validarDescripcion($data['descripcion'])){
            $errores[]="Ha Fallado la validacion de la descripcion";
        }

    }
    // si se quiere modificar la imagen 
    if(!empty($data['imagen'])){
        if(!$validacion->validarImagen($data['imagen'])){
            $errores[]="La extension de la imagen no es valida";
        }

    }
    // si se quiere modificar la url
    if(!empty($data['url'])){
        if(!$validacion->validarURL($data['url'])){
            $errores[]="La url es invalida";
        }
    }
    
    if(empty($errores)){
        $juegos = new Juegos();
        $put = $juegos->put($data);
        if($put){
        $response->getBody()->write(json_encode('{"msg": "Juego  Actualizado correctamente."}', JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json')->withStatus(200); 
        }
        else{
            $response->getBody()->write(json_encode('{"error": "Error al actualizar  el juego."}', JSON_UNESCAPED_UNICODE));
            $response->withHeader('Content-Type', 'application/json')->withStatus(404); 
        }
    }else{
         $response->getBody()->write(json_encode($errores, JSON_UNESCAPED_UNICODE));
         $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
    }
    return $response;
    
});

//k) Eliminar un juego: el endpoint debe permitir enviar el id del juego y eliminarlo de la tabla.
$app->delete('/juegos/{id}', function (Request $request, Response $response, $args) {
    $juegos = new Juegos();
    $delete = $juegos->delete($args['id']);
    if($delete){
        //Si se ejecutó correctamente la consulta
        if($delete->rowCount() > 0){
            $response->getBody()->write(json_encode('{"msg": "Juego eliminado."}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode('{"msg": "ID no encontrado."}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }else{
        //Si ocurrió un error
        $response->getBody()->write(json_encode('{"error": "Ocurrió algún error en la consulta."}', JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

//l) Obtener todos los juegos: implemente un endpoint para obtener todos los juegos de la tabla.
$app->get('/juegos', function (Request $request, Response $response, $args) {
    $juegos = new Juegos();
    $get = $juegos->get();
    if($get){
        //Si se ejecutó correctamente la consulta
        if($get->rowCount() > 0){
            $response->getBody()->write(json_encode($get->fetchAll(), JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode('{"msg": "No se encontraron datos en la BD."}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }else{
        //Si ocurrió un error
        $response->getBody()->write(json_encode('{"error": "Ocurrió algún error en la consulta."}', JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

//m) Buscar juegos: implementar un endpoint que permita buscar juegos por nombre, plataforma y género. El endpoint deberá aceptar un nombre, un id de género, un id de plataforma y un orden por nombre (ASC o DESC)
$app->get('/buscar', function (Request $request, Response $response, $args) {
    //getQueryParams() es un método de $request que captura los parametros en la URL, lo usaremos para capturar el nombre, plataforma y género, de juegos
    $params = $request->getQueryParams();

    $busqueda = new Juegos();
    $get = $busqueda->buscar($params);

    if($get){
        //Si se ejecutó correctamente la consulta
        if($get->rowCount() > 0){
            $response->getBody()->write(json_encode($get->fetchAll(), JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode('{"msg": "No se encontraron datos en la BD."}', JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }else{
        //Si ocurrió un error
        $response->getBody()->write(json_encode('{"error": "Ocurrió algún error en la consulta."}', JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

$app->run();

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

//Función para construir respuestas JSON para cada ruta
function sendJSON($response, $type, $message, $status_code) {
    //Se recibe $response que será el de SLIM
    //Se recibe el $type que es el nombre de la propiedad
    //Se recibe el $message que será el valor de la propiedad
    //Se recibe el $status_code que será el código de estado para la respuesta
    //Si $type es 'arr' significa que recepcionamos un arreglo y no nu string json
    if($type === 'arr'){
        $response = $response->withHeader('Content-Type', 'application/json')->withStatus($status_code);
        $response->getBody()->write(json_encode($message, JSON_UNESCAPED_UNICODE));
    }else{
        $respuesta[$type] = $message;
        $response = $response->withHeader('Content-Type', 'application/json')->withStatus($status_code);
        $response->getBody()->write(json_encode($respuesta, JSON_UNESCAPED_UNICODE));
    }
    $response->withHeader('Access-Control-Allow-Origin','*');
    //Retornamos respuesta generada
    return $response;
}  





$app = AppFactory::create();    

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


$app->get('/', function (Request $request, Response $response, $args) {
    return sendJSON($response, 'msg', 'Bienvenido a la API!', 200);
});

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
            if($post->rowCount()){
                //Si fue exitosa entra acá
                $endpoint = sendJSON($response, 'msg', 'Género insertado correctamente.', 200);
            }else{
                //Si obtuvo un error entra acá
                $endpoint = sendJSON($response, 'error', 'Error al insertar el género.', 400);
            }
        }else{
            //Si la validación falló entra acá
            $endpoint = sendJSON($response, 'error', 'Falló la validación.', 400);
        }
    }else{
        //Si no existían datos entra acá
        $endpoint = sendJSON($response, 'error', 'No se envió ningún dato.', 400);
    }
    //Retornamos respuesta
    return $endpoint;
});

//b) Actualizar información de un género: implementar un endpoint para actualizar la información de un género existente en la tabla de géneros. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
$app->put('/generos/{id}', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = json_decode($request->getBody()->getContents());
    $id = $args['id'];
    //Comprobamos que no sean nulos
    if(isset($data)){
        //Iniciamos un nuevo objeto de Validación
        $validacion = new Validacion();
        //Comprobamos que los datos sean válidos
        if($validacion->validarNombre($data->nombre)){
            //Iniciamos un nuevo objeto del tipo correspondiente
            $generos = new Generos();
            //Ejecutamos método correspondiente
            $put = $generos->put($id, $data);
            //Comprobamos estado de la ejecución
            if($put->rowCount()){
                //Si fue exitosa entra acá
                $endpoint = sendJSON($response, 'msg', 'Género actualizado correctamente.', 200);
            } else {
                //Si obtuvo un error entra acá
                $endpoint = sendJSON($response, 'error', 'El nombre es el mismo al actual.', 400);
            }
        } else{
            //Si la validación falló entra acá
            $endpoint = sendJSON($response, 'error', 'Error en la validación del nombre.', 400);
        }
    }else{
        //Si no existían datos entra acá
        $endpoint = sendJSON($response, 'error', 'No se han enviado datos.', 400);
    }
    //Retornamos respuesta
    return $endpoint;
});

//c) Eliminar un género: el endpoint debe permitir enviar el id del genero y eliminarlo de la tabla.
$app->delete('/generos/{id}', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $id = $args['id'];
    //Comprobamos que no sean nulos
    if(isset($id)){
        //Iniciamos un nuevo objeto del tipo correspondiente
        $generos = new Generos();
        //Comprobamos si existe algún juego vinculado al id a borrar
        if($generos->enJuego($id)){
            //Si existe retornamos un error
            $endpoint = sendJSON($response, 'error', 'No se ha podido eliminar el género, porque existen juegos vinculados al mismo.', 400);
        }else{
            //Ejecutamos método correspondiente
            $delete = $generos->delete($id);
            //Comprobamos estado de la ejecución
            if($delete->rowCount()){
                //Si fue exitosa entra acá
                $endpoint = sendJSON($response, 'msg', 'Género eliminado correctamente.', 200);
            }else{
                //Si obtuvo un error entra acá
                $endpoint = sendJSON($response, 'error', 'No se ha encontrado el género a eliminar.', 404);
            }
        }
    }else{
        //Si no existían datos entra acá
        $endpoint = sendJSON($response, 'error', 'No se envió el id del género a borrar.', 400);
    }
    //Retornamos respuesta
    return $endpoint;
});

//d) Obtener todos los géneros: implemente un endpoint para obtener todos los géneros de la tabla.
$app->get('/generos', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = $request->getQueryParams();
    //Iniciamos un nuevo objeto del tipo correspondiente
    $generos = new Generos();
    //Ejecutamos método correspondiente
    $get = $generos->get($data);
    //Comprobamos estado de la ejecución
    if($get->rowCount()){
        //Si fue exitosa entra acá
        $endpoint = sendJSON($response, 'arr', $get->fetchAll(), 200);
    } else {
        //Si obtuvo un error entra acá
        $endpoint = sendJSON($response, 'error', 'No se encontraron datos en la BD.', 200);
    }
    //Retornamos respuesta
    return $endpoint;
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
            if($post->rowCount()){
                //Si fue exitosa entra acá
                $endpoint = sendJSON($response, 'msg', 'Plataforma insertada correctamente.', 200);
            }else{
                //Si obtuvo un error entra acá
                $endpoint = sendJSON($response, 'error', 'Error al insertar la plataforma.', 400);
            }
        }else{
            //Si la validación falló entra acá
            $endpoint = sendJSON($response, 'error', 'Error en la validación del nombre.', 400);
        }
    }else{
        //Si no existían datos entra acá
        $endpoint = sendJSON($response, 'error', 'No se envió ningún dato.', 400);
    }
    //Retornamos respuesta
    return $endpoint;
});

//f) Actualizar información de una plataforma: implementar un endpoint para actualizar la información de una plataforma existente en la tabla de plataformas. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
$app->put('/plataformas/{id}', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = json_decode($request->getBody()->getContents());
    $id = $args['id'];
    //Comprobamos que no sean nulos
    if(isset($data)){
        //Iniciamos un nuevo objeto de Validación
        $validacion = new Validacion();
        //Comprobamos que los datos sean válidos
        if($validacion->validarNombre($data->nombre)){
            //Iniciamos un nuevo objeto del tipo correspondiente
            $plataformas = new Plataformas();
            //Ejecutamos método correspondiente
            $put = $plataformas->put($id,$data);
            //Comprobamos estado de la ejecución
            if($put->rowCount()){
                //Si fue exitosa entra acá
                $endpoint = sendJSON($response, 'msg', 'Plataforma actualizada correctamente.', 200);
            }else{
                //Si obtuvo un error entra acá
                $endpoint = sendJSON($response, 'error', 'El nombre es el mismo al actual.', 400);
            }
        }else{
            //Si la validación falló entra acá
            $endpoint = sendJSON($response, 'error', 'Fallo la validación del nombre.', 400);
        }
    }else{
        //Si no existían datos entra acá
        $endpoint = sendJSON($response, 'error', 'No se envió ningún dato.', 400);
    }
    //Retornamos respuesta
    return $endpoint;
});

//g) Eliminar una plataforma: el endpoint debe permitir enviar el id de la plataforma y eliminarla de la tabla.
$app->delete('/plataformas/{id}', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $id = $args['id'];
    //Comprobamos que no sean nulos
    if(isset($id)){
        //Iniciamos un nuevo objeto del tipo correspondiente
        $plataformas = new Plataformas();
        //Comprobamos si existe algún juego vinculado al id a borrar
        if($plataformas->enJuego($id)){
            //Si existe retornamos un error
            $endpoint = sendJSON($response, 'error', 'No se ha podido eliminar la plataforma, porque existen juegos vinculados al mismo.', 400);
        }else{
            //Ejecutamos método correspondiente
            $delete = $plataformas->delete($id);
            //Comprobamos estado de la ejecución
            if($delete->rowCount()){
                //Si fue exitosa entra acá
                $endpoint = sendJSON($response, 'msg', 'Plataforma eliminada correctamente.', 200);
            } else {
                //Si obtuvo un error entra acá
                $endpoint = sendJSON($response, 'error', 'No se ha encontrado la plataforma a eliminar.', 404);
            }
        }
    }else{
        //Si no existían datos entra acá
        $endpoint = sendJSON($response, 'error', 'No se envió el id de la plataforma a eliminar', 400);
    }
    //Retornamos respuesta
    return $endpoint;
});

//h) Obtener todas las plataformas: implemente un endpoint para obtener todos los géneros de la tabla.
$app->get('/plataformas', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = $request->getQueryParams();
    //Iniciamos un nuevo objeto del tipo correspondiente
    $plataformas = new Plataformas();
    //Ejecutamos método correspondiente
    $get = $plataformas->get($data);
    //Comprobamos estado de la ejecución
    if($get->rowCount()){
        //Si fue exitosa entra acá
        $endpoint = sendJSON($response, 'arr', $get->fetchAll(), 200);
    } else {
        //Si obtuvo un error entra acá
        $endpoint = sendJSON($response, 'error', 'No se encontraron datos en la BD.', 200);
    }
    //Retornamos respuesta
    return $endpoint;
});

//i) Crear un nuevo juego: implementar un endpoint para crear un nuevo juego en la tabla de juegos. El endpoint debe permitir enviar el nombre, imagen, descripción, plataforma, URL y género.
$app->post('/juegos', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = json_decode($request->getBody()->getContents());
    //Comprobamos que no sean nulos
    if(isset($data)){
        //Iniciamos un nuevo objeto de Validación
        $validacion = new Validacion();
        //Iniciamos un arreglo de errores
        $errores = [];
        //Comprobamos que los datos sean válidos
        if (!isset($data->nombre)) {
            //Si no se envió el nombre
            $errores['error_nombre'] = 'No se envió el nombre';
        }else{
            //Si se envió el nombre comprobamos con método de la clase Validacion
            (!$validacion->validarNombre($data->nombre)) ? $errores['error_nombre'] = 'Nombre inválido' : '';
        }
        //Si no se envió la imagen
        (!isset($data->imagen)) ? $errores['error_imagen'] = 'No se envió la imagen' : '';
        if (!isset($data->imagen_tipo)) {
            //Si no se envió el tipo de imagen
            $errores['error_imagen'] = 'No se envió el tipo de imagen';
        }else{
            //Si se envió el tipo de imagen comprobamos con método de la clase Validacion
            (!$validacion->validarImagen($data->imagen_tipo)) ? $errores['error_imagen_tipo'] = 'Extensión de imagen inválida' : '';
        }
        if (!isset($data->descripcion)) {
            //Si no se envió la descripción
            $errores['error_descripcion'] = 'No se envió la descripción';
        }else{
            //Si se envió la descripción comprobamos con método de la clase Validacion
            (!$validacion->validarDescripcion($data->descripcion)) ? $errores['error_descripcion'] = 'Descripción inválida' : '';
        }
        if (!isset($data->url)) {
            //Si no se envió la URL
            $errores['error_url'] = 'No se envió la URL';
        }else{
            //Si se envió la URL comprobamos con método de la clase Validacion
            (!$validacion->validarURL($data->url)) ? $errores['error_url'] = 'URL inválida' : '';
        }
        //Si no se envió el género
        (!isset($data->id_genero)) ? $errores['error_genero'] = 'No se seleccionó ningún género' : '';
        //Si no se envió la plataforma
        (!isset($data->id_plataforma)) ? $errores['error_plataforma'] = 'No se seleccionó ninguna plataforma' : '';
        //Comprobamos que $errores esté vacío
        if(empty($errores)){
            //Iniciamos un nuevo objeto del tipo corresp ndiente
            $juegos = new Juegos();
            //Ejecutamos método correspondiente
            $post = $juegos->post($data);
            //Comprobamos estado de la ejecución
            if($post->rowCount()){
                //Si fue exitosa entra acá
                $endpoint = sendJSON($response, 'msg', 'Juego cargado exitosamente.', 200);
            }else{
                //Si obtuvo un error entra acá
                $endpoint = sendJSON($response, 'error', 'Falló la carga del juego.', 400);
            }
        }else{
            //Si la validación falló entra acá
            $endpoint = sendJSON($response, 'arr', $errores, 400);
        }
    }else{
        //Si no existían datos entra acá
        $endpoint = sendJSON($response, 'error', 'No se enviaron datos.', 400);
    }
    //Retornamos respuesta
    return $endpoint;
});

//j) Actualizar información de un juego: implementar un endpoint para actualizar la información de un juego existente en la tabla de juegos. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
$app->put('/juegos/{id}', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = json_decode($request->getBody()->getContents());
    $id = $args['id'];
    //Comprobamos que no sean nulos
    if(isset($data)){
        //Iniciamos un nuevo objeto de Validación
        $validacion = new Validacion();
        //Iniciamos un arreglo de errores
        $errores = [];
        //Comprobamos que los datos sean válidos
        if (isset($data->nombre)) {
            //Si se envió el nombre comprobamos con método de la clase Validacion
            (!$validacion->validarNombre($data->nombre)) ? $errores['error_nombre'] = 'Nombre inválido' : '';
        }
        //Si no se envió la imagen
        if ((isset($data->imagen)) and (isset($data->imagen_tipo))) {
          //Si se envio la imagen, validamos su tipo 
            (!$validacion->validarImagen($data->imagen_tipo)) ? $errores['error_imagen_tipo'] = 'Extensión de imagen inválida' : '';
        }
        if (isset($data->descripcion)) {
            //Si se envió la descripción comprobamos con método de la clase Validacion
            (!$validacion->validarDescripcion($data->descripcion)) ? $errores['error_descripcion'] = 'Descripción inválida' : '';
        }

        if (isset($data->url)) {
            //Si se envió la URL comprobamos con método de la clase Validacion
            (!$validacion->validarURL($data->url)) ? $errores['error_url'] = 'URL inválida' : '';
        }
        //Comprobamos que $errores esté vacío
        if(empty($errores)){
            //Iniciamos un nuevo objeto del tipo corresp ndiente
            $juegos = new Juegos();
            //Ejecutamos método correspondiente
            $post = $juegos->put($id,$data);
            //Comprobamos estado de la ejecución
            if($post->rowCount()){
                //Si fue exitosa entra acá
                $endpoint = sendJSON($response, 'msg', 'Juego actualizado exitosamente.', 200);
            }else{
                //Si obtuvo un error entra acá
                $endpoint = sendJSON($response, 'error', 'No se encontró el juego a actualizar.', 200);
            }
        }else{
            //Si la validación falló entra acá
            $endpoint = sendJSON($response, 'arr', $errores, 400);
        }
    }else{
        //Si no existían datos entra acá
        $endpoint = sendJSON($response, 'error', 'No se enviaron datos.', 400);
    }
    //Retornamos respuesta
    return $endpoint;
});

//k) Eliminar un juego: el endpoint debe permitir enviar el id del juego y eliminarlo de la tabla.
$app->delete('/juegos/{id}', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $id = $args['id'];
    //Comprobamos que no sean nulos
    if(isset($id)){
        //Iniciamos un nuevo objeto del tipo correspondiente
        $juegos = new Juegos();
        //Ejecutamos método correspondiente
        $delete = $juegos->delete($id);
        //Comprobamos estado de la ejecución
        if($delete->rowCount()){
            //Si fue exitosa entra acá
            $endpoint = sendJSON($response, 'msg', 'Juego eliminado correctamente.', 200);
        } else {
            //Si obtuvo un error entra acá
            $endpoint = sendJSON($response, 'error', 'No se ha encontrado el juego a eliminar.', 404);
        }
    }else{
        //Si no existían datos entra acá
        $endpoint = sendJSON($response, 'msg', 'No se envió el ID del juego a eliminar.', 400);
    }
    //Retornamos respuesta
    return $endpoint;
});

//l) Obtener todos los juegos: implemente un endpoint para obtener todos los juegos de la tabla.
$app->get('/juegos', function (Request $request, Response $response, $args) {
    //Obtenemos los datos
    $data = $request->getQueryParams();
    
    $juegos = new Juegos();
    //Ejecutamos método correspondiente
    $get = $juegos->buscar($data);
    //Comprobamos estado de la ejecución
    if($get->rowCount()){
        //Si fue exitosa entra acá
        $endpoint = sendJSON($response, 'arr', $get->fetchAll(), 200);
    } else {
        //Si obtuvo un error entra acá
        $endpoint = sendJSON($response, 'error', 'No se encontraron datos en la BD.', 400);
    }

    //Retornamos respuesta
    return $endpoint;
});



//m) Buscar juegos: implementar un endpoint que permita buscar juegos por nombre, plataforma y género. El endpoint deberá aceptar un nombre, un id de género, un id de plataforma y un orden por nombre (ASC o DESC)
$app->map(["GET", "POST", "PUT","PATCH","DELETE"], '/{routes:.+}', function (Request $request, Response $response) {
    throw new \Slim\Exception\HttpNotFoundException($request);
})->setName('notFound');

// Middleware para manejar la excepción HttpNotFoundException y generar una respuesta 404
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(\Slim\Exception\HttpNotFoundException::class, function (Request $request, Throwable $exception, bool $displayErrorDetails) use ($app) {
    $response = $app->getResponseFactory()->createResponse();
    return sendJSON($response, 'error', 'Ruta no encontrada.', 404);
});






//Corremos SLIM
$app->run();

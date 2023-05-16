<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Models\Db as Juegos;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

//a) Crear un nuevo género: implementar un endpoint para crear un nuevo genero en la tabla de géneros. El endpoint debe permitir enviar el nombre.
$app->post('/juegos', function (Request $request, Response $response, $args) {
    $response->getBody()->write('Crear juego');
    return $response;
});
//b) Actualizar información de un género: implementar un endpoint para actualizar la información de un género existente en la tabla de géneros. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
$app->get('/juegos', function (Request $request, Response $response, $args) {
    $response->getBody()->write('Listado de juegos');
    return $response;
});
//c) Eliminar un género: el endpoint debe permitir enviar el id del genero y eliminarlo de la tabla.
//d) Obtener todos los géneros: implemente un endpoint para obtener todos los géneros de la tabla.
//e) Crear una nueva plataforma: implementar un endpoint para crear una nueva plataforma en la tabla de plataformas. El endpoint debe permitir enviar el nombre.
//f) Actualizar información de una plataforma: implementar un endpoint para actualizar la información de una plataforma existente en la tabla de plataformas. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
//g) Eliminar una plataforma: el endpoint debe permitir enviar el id de la plataforma y eliminarla de la tabla.
//h) Obtener todos los géneros: implemente un endpoint para obtener todos los géneros de la tabla.
//i) Crear un nuevo juego: implementar un endpoint para crear un nuevo juego en la tabla de juegos. El endpoint debe permitir enviar el nombre, imagen, descripción, plataforma, URL y género.
//j) Actualizar información de un juego: implementar un endpoint para actualizar la información de un juego existente en la tabla de juegos. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
//k) Eliminar un juego: el endpoint debe permitir enviar el id del juego y eliminarlo de la tabla.
//l) Obtener todos los juegos: implemente un endpoint para obtener todos los juegos de la tabla.
//m) Buscar juegos: implementar un endpoint que permita buscar juegos por nombre, plataforma y género. El endpoint deberá aceptar un nombre, un id de género, un id de plataforma y un orden por nombre (ASC o DESC)
$app->get('/generos', function (Request $request, Response $response, $args) {
    $data = Juegos::getGeneros();
    $response->getBody()->write('Listado de géneros');
    return $response;
});

$app->run();

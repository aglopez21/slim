<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Models\Db;
use App\Models\Juegos as Juegos;
use App\Models\Generos as Generos;
use App\Models\Plataformas as Plataformas;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $generos = new Generos();
    $get = $generos::get();
    $response->getBody()->write('Inicio');
    return $response;
});
//a) Crear un nuevo género: implementar un endpoint para crear un nuevo genero en la tabla de géneros. El endpoint debe permitir enviar el nombre.
$app->post('/generos', function (Request $request, Response $response, $args) {
    $generos = new Generos();
    $post = $generos::post();
    $response->getBody()->write('Crear género');
    return $response;
});
//b) Actualizar información de un género: implementar un endpoint para actualizar la información de un género existente en la tabla de géneros. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
$app->put('/generos', function (Request $request, Response $response, $args) {
    $generos = new Generos();
    $put = $generos::put();
    $response->getBody()->write('Actualizar género');
    return $response;
});
//c) Eliminar un género: el endpoint debe permitir enviar el id del genero y eliminarlo de la tabla.
$app->delete('/generos', function (Request $request, Response $response, $args) {
    $generos = new Generos();
    $delete = $generos::delete();
    $response->getBody()->write('Borrar género');
    return $response;
});
//d) Obtener todos los géneros: implemente un endpoint para obtener todos los géneros de la tabla.
$app->get('/generos', function (Request $request, Response $response, $args) {
    $generos = new Generos();
    $get = $generos::get();
    $response->getBody()->write('Listado de géneros');
    return $response;
});
//e) Crear una nueva plataforma: implementar un endpoint para crear una nueva plataforma en la tabla de plataformas. El endpoint debe permitir enviar el nombre.
$app->post('/plataformas', function (Request $request, Response $response, $args) {
    $plataformas = new Plataformas();
    $post = $plataformas::post();
    $response->getBody()->write('Crear plataforma');
    return $response;
});
//f) Actualizar información de una plataforma: implementar un endpoint para actualizar la información de una plataforma existente en la tabla de plataformas. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
$app->put('/plataformas', function (Request $request, Response $response, $args) {
    $plataformas = new Plataformas();
    $put = $plataformas::put();
    $response->getBody()->write('Actualizar plataforma');
    return $response;
});
//g) Eliminar una plataforma: el endpoint debe permitir enviar el id de la plataforma y eliminarla de la tabla.
$app->delete('/plataformas', function (Request $request, Response $response, $args) {
    $plataformas = new Plataformas();
    $delete = $plataformas::delete();
    $response->getBody()->write('Borrar plataforma');
    return $response;
});
//h) Obtener todas las plataformas: implemente un endpoint para obtener todos los géneros de la tabla.
$app->get('/plataformas', function (Request $request, Response $response, $args) {
    $plataformas = new Plataformas();
    $get = $plataformas::get();
    $response->getBody()->write('Listado de plataformas');
    return $response;
});
//i) Crear un nuevo juego: implementar un endpoint para crear un nuevo juego en la tabla de juegos. El endpoint debe permitir enviar el nombre, imagen, descripción, plataforma, URL y género.
$app->post('/juegos', function (Request $request, Response $response, $args) {
    $juegos = new Juegos();
    $post = $juegos::post();
    $response->getBody()->write('Crear juego');
    return $response;
});
//j) Actualizar información de un juego: implementar un endpoint para actualizar la información de un juego existente en la tabla de juegos. El endpoint debe permitir enviar el id y los campos que se quieran actualizar
$app->put('/juegos', function (Request $request, Response $response, $args) {
    $juegos = new Juegos();
    $put = $juegos::put();
    $response->getBody()->write('Actualizar juego');
    return $response;
});
//k) Eliminar un juego: el endpoint debe permitir enviar el id del juego y eliminarlo de la tabla.
$app->delete('/juegos', function (Request $request, Response $response, $args) {
    $juegos = new Juegos();
    $delete = $juegos::delete();
    $response->getBody()->write('Borrar juego');
    return $response;
});
//l) Obtener todos los juegos: implemente un endpoint para obtener todos los juegos de la tabla.
$app->get('/juegos', function (Request $request, Response $response, $args) {
    $juegos = new Juegos();
    $get = $juegos::get();
    $response->getBody()->write('Listado de juegos');
    return $response;
});
//m) Buscar juegos: implementar un endpoint que permita buscar juegos por nombre, plataforma y género. El endpoint deberá aceptar un nombre, un id de género, un id de plataforma y un orden por nombre (ASC o DESC)
$app->get('/buscar', function (Request $request, Response $response, $args) {
    $busqueda = new Juegos();
    $get = $busqueda::buscar();
    $response->getBody()->write('Búsqueda de juegos');
    return $response;
});

$app->run();

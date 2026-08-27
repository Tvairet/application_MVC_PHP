<?php
/**
 * Front Controller
 */

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_URL', '/application_MVC_PHP');

require_once __DIR__ . '/../vendor/autoload.php';

use Buki\Router\Router;
use Dotenv\Dotenv;

// Chargement des variables d'environnement
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Instanciation du routeur
$router = new Router([
    'paths' => [
        'controllers' => __DIR__ . '/../app/Controllers',
        'middlewares' => __DIR__ . '/../app/Middlewares',
    ],
    'namespaces' => [
        'controllers' => 'App\Controllers',
        'middlewares' => 'App\Middlewares',
    ],
    'base_folder' => '/application_MVC_PHP',
]);

// Route d'accueil
$router->get('/', 'HomeController@index');

// Routes d'authentification
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@authenticate');
$router->get('/logout', 'AuthController@logout');

// Routes protégées par authentification (Trajets)
$router->group('/ride', function($router) {
    $router->get('/create', 'RideController@create', ['before' => 'AuthMiddleware']);
    $router->post('/store', 'RideController@store', ['before' => 'AuthMiddleware']);
    
    // Modification et suppression
    $router->get('/edit/:id', 'RideController@edit', ['before' => 'AuthMiddleware']);
    $router->post('/update/:id', 'RideController@update', ['before' => 'AuthMiddleware']);
    $router->any('/delete/:id', 'RideController@delete', ['before' => 'AuthMiddleware']);
});

// Routes d'administration
$router->group('/admin', function($router) {
    $router->get('/', 'AdminController@index', ['before' => 'AdminMiddleware']);
    
    // Gestion des utilisateurs
    $router->get('/users', 'AdminController@users', ['before' => 'AdminMiddleware']);
    
    // Gestion des agences
    $router->get('/agencies', 'AdminController@agencies', ['before' => 'AdminMiddleware']);
    $router->get('/agencies/create', 'AdminController@createAgency', ['before' => 'AdminMiddleware']);
    $router->post('/agencies/store', 'AdminController@storeAgency', ['before' => 'AdminMiddleware']);
    $router->get('/agencies/edit/:id', 'AdminController@editAgency', ['before' => 'AdminMiddleware']);
    $router->post('/agencies/update/:id', 'AdminController@updateAgency', ['before' => 'AdminMiddleware']);
    $router->any('/agencies/delete/:id', 'AdminController@deleteAgency', ['before' => 'AdminMiddleware']);
    
    // Modération des trajets
    $router->get('/rides', 'AdminController@rides', ['before' => 'AdminMiddleware']);
    $router->any('/rides/delete/:id', 'AdminController@deleteRide', ['before' => 'AdminMiddleware']);
});

// Exécution du routeur
$router->run();
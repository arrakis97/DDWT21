<?php
/**
 * Controller
 *
 * Database-driven Webtechnology
 * Taught by Stijn Eikelboom
 * Based on code by Reinard van Dalen
 */

/* Require composer autoloader */
require __DIR__ . '/vendor/autoload.php';

/* Include model.php */
include 'model.php';

/* Connect to DB */
$db = connect_db('localhost', 'ddwt21_week3', 'ddwt21', 'ddwt21');

/* Create Router instance */
$router = new \Bramus\Router\Router();

// Add routes here
$router->mount('/api', function () use ($router, $db){
    http_type_content();

    /* GET reading for all series */
    $router->get('/series', function () use ($db) {
        $series = get_series($db);
        echo json_encode($series, JSON_PRETTY_PRINT);
    });
});

$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    p_print("404 error: page was not found.");
});

/* Run the router */
$router->run();

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

    /* GET for reading individual series */
    $router->get('/series/(\d+)', function ($id) use ($db) {
        $series_info = get_series_info($db, $id);
        echo json_encode($series_info, JSON_PRETTY_PRINT);
    });

    /* DELETE for deleting individual series */
    $router->delete('/series/(\d+)', function ($id) use ($db) {
        $feedback = remove_series($db, $id);
        echo json_encode($feedback, JSON_PRETTY_PRINT);
    });
});

$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    echo "404 error: page was not found.";
});

/* Run the router */
$router->run();
